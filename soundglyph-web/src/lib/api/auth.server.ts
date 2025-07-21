import { AUTH_SECRET } from '$env/static/private';
import type { Cookies } from '@sveltejs/kit';
import crypto from 'crypto';
import jwt from 'jsonwebtoken';

const ALGORITHM = 'aes-256-cbc'; // symmetric cipher

export function auth(cookies: Cookies) {
	return new Auth(cookies);
}

export class Auth {
	private cookies: Cookies;
	private key: Buffer;

	constructor(cookies: Cookies) {
		this.cookies = cookies;

		// AUTH_SECRET must be a 32-byte hex string
		if (!AUTH_SECRET) {
			throw new Error('Missing AUTH_SECRET env var');
		}

		this.key = Buffer.from(AUTH_SECRET, 'hex');

		if (this.key.length !== 32) {
			throw new Error('AUTH_SECRET must decode to 32 bytes');
		}
	}

	// —————— Encryption helpers ——————
	private encrypt(plain: string): string {
		const iv = crypto.randomBytes(16);
		const cipher = crypto.createCipheriv(ALGORITHM, this.key, iv);
		const encrypted = Buffer.concat([cipher.update(plain, 'utf8'), cipher.final()]);

		// store as iv:cipher
		return iv.toString('hex') + ':' + encrypted.toString('hex');
	}

	private decrypt(ciphertext: string): string {
		const [ivHex, dataHex] = ciphertext.split(':');

		if (!ivHex || !dataHex) {
			throw new Error('Invalid payload');
		}

		const iv = Buffer.from(ivHex, 'hex');
		const data = Buffer.from(dataHex, 'hex');
		const decipher = crypto.createDecipheriv(ALGORITHM, this.key, iv);
		const decrypted = Buffer.concat([decipher.update(data), decipher.final()]);

		return decrypted.toString('utf8');
	}

	// —————— Public API ——————

	/** Save both tokens as encrypted, HTTP-only cookies */
	saveTokens(tokens: { accessToken: string; refreshToken?: string }) {
		const accessEnc = this.encrypt(tokens.accessToken);

		const cookieOpts = {
			httpOnly: true,
			path: '/',
			sameSite: 'lax' as const,
			secure: process.env.NODE_ENV === 'production'
		};

		const seconds = 60 * 60;

		this.cookies.set('access_token', accessEnc, {
			...cookieOpts,
			maxAge: seconds // 1 hour
		});

		this.cookies.set('expiration', (new Date().getTime() + seconds * 1000).toString(), {
			...cookieOpts,
			maxAge: 60 * 60 * 3 // 3 hour
		});

		if (tokens.refreshToken) {
			const refreshEnc = this.encrypt(tokens.refreshToken);

			this.cookies.set('refresh_token', refreshEnc, {
				...cookieOpts,
				maxAge: 60 * 60 * 24 * 30 // e.g. 30 days
			});
		}
	}

	/** Return decrypted JWT (or null) */
	getAuthToken(): string | null {
		const enc = this.cookies.get('access_token');

		if (!enc) {
			return null;
		}

		try {
			return this.decrypt(enc);
		} catch {
			return null;
		}
	}

	/** Delete both access and refresh token cookies */
	deleteTokens() {
		const cookieOpts = {
			httpOnly: true,
			path: '/',
			sameSite: 'lax' as const,
			secure: process.env.NODE_ENV === 'production'
		};

		// Delete the cookies
		this.cookies.delete('access_token', { ...cookieOpts, maxAge: 60 * 60 });
		this.cookies.delete('refresh_token', cookieOpts);
		this.cookies.delete('active_space_id', cookieOpts);
	}

	/** Quick boolean check: do we have a non-expired access token? */
	isAuthenticated(): boolean {
		const token = this.getAuthToken();
		if (!token) return false;
		try {
			// if your backend uses a public key, swap in jwt.verify(token, PUBLIC_KEY)
			const payload = jwt.decode(token) as { exp?: number } | null;
			return !!payload && (payload.exp ?? 0) * 1000 > Date.now();
		} catch {
			return false;
		}
	}
}
