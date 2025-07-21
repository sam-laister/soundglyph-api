// src/routes/api/auth/login/+server.ts
import { json } from '@sveltejs/kit';
import jwt from 'jsonwebtoken';
import type { RequestHandler } from './$types';
import { authApi } from '@/api/api.server';
import { auth } from '@/api/auth.server';

export const POST: RequestHandler = async ({ cookies, request, locals }) => {
	try {
		const { email, password } = await request.json();

		if (!email) {
			return json(
				{
					success: false,
					message: 'No email given'
				},
				{ status: 400 }
			);
		}

		if (!password) {
			return json(
				{
					success: false,
					message: 'No password given'
				},
				{ status: 400 }
			);
		}

		let token;

		const { ok, status, data: response } = await authApi().login({ email, password });
		token = response.token;

		if ([401, 403, 404].includes(status)) {
			return json(
				{
					success: false,
					message: 'Invalid username or password'
				},
				{ status: 401 }
			);
		} else if (!ok) {
			// For other errors, return generic error
			return json(
				{
					success: false,
					message: 'Login failed. Please try again.'
				},
				{ status: 500 }
			);
		}

		if (!token) {
			return json(
				{
					success: false,
					message: 'No token received from authentication service'
				},
				{ status: 500 }
			);
		}

		// Save tokens as encrypted cookies
		auth(cookies).saveTokens({
			accessToken: token
			// refreshToken: null
		});

		// Decode token to get user data for client
		try {
			const payload = jwt.decode(token) as any;
			const user = {
				id: payload.sub || payload.userId,
				email: payload.email,
				name: payload.name
				// Add other non-sensitive fields from your JWT payload
			};

			const expiresAt = cookies.get('expiration');

			return json({
				success: true,
				user,
				expiresAt
			});
		} catch (decodeError) {
			// Token was saved but we couldn't decode it for client data
			// This shouldn't normally happen, but we'll handle it gracefully
			return json({
				success: true,
				message: 'Login successful'
			});
		}
	} catch (error) {
		console.error('Login error:', error);

		return json(
			{
				success: false,
				message: 'Invalid request format'
			},
			{ status: 400 }
		);
	}
};
