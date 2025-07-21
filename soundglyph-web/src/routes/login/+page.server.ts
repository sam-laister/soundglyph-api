import { authApi } from '@/api/api.server';
import type { Actions, PageServerLoad } from './$types';
import { redirect } from '@sveltejs/kit';
import { auth } from '@/api/auth.server';

export const load: PageServerLoad = async ({ url, cookies }) => {
	// Redirect path
	let redirectPath = '/';

	if (url.searchParams.has('goto')) {
		redirectPath = url.searchParams.get('goto')?.toString() ?? '/';
	} else if (cookies.get('login_redirect')) {
		redirectPath = cookies.get('login_redirect')?.toString() ?? '/';
	}

	return { redirectPath };
};

export const actions = {
	login: async ({ cookies, request, locals, url }) => {
		const data = await request.formData();
		console.log(data);

		const email = data.get('email')?.toString();
		const password = data.get('password')?.toString();
		const path = data.get('path')?.toString() ?? '/';

		if (!email) {
			return {
				success: false,
				message: 'No email given'
			};
		} else if (!password) {
			return {
				success: false,
				message: 'No password given'
			};
		}

		let token;
		let {
			status,
			statusText,
			ok,
			data: loginResponse
		} = await authApi().login({ email, password });
		console.log('response', loginResponse);

		if (status.toString().startsWith('4')) {
			return {
				message: 'Invalid username or password'
			};
		} else if (!ok) {
			return {
				message: 'Something went wrong...'
			};
		}

		token = loginResponse?.token;
		console.log('login token', loginResponse);

		auth(cookies).saveTokens({
			accessToken: token ?? ''
		});

		// Redirect
		redirect(303, path);
	}
} satisfies Actions;
