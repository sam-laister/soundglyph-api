import { redirect, type Cookies, type Handle } from '@sveltejs/kit';
import { auth } from '@/api/auth.server';
import { adminApi } from '@/api/api.server';
import { redirectToLogin } from './lib';

export const handle: Handle = async ({ event, resolve }) => {
    const { locals, url, cookies } = event;

    if (url.pathname.startsWith('/s/')) {
        return resolve(event);
    }

    const authLib = auth(cookies);

    // Get access token
    const authToken = authLib.getAuthToken() ?? undefined;

    // If not auth then redirect
    if (url.pathname.startsWith('/auth') || url.pathname.startsWith('/api/auth')) {
        return resolve(event);
    } else if (!authLib.isAuthenticated()) {
        return redirectToLogin(url, cookies);
    }

    // Add access token
    locals.accessToken = authToken as string;

    // If authenticated
    if (locals.accessToken) {
        const api = adminApi(locals.accessToken ?? "");

        const { data: currentUser, status, ok } = await api.currentUser().me();

        if (!ok) {
            if (status === 401) {
                authLib.deleteTokens();
            }

            return redirectToLogin(url, cookies);
        }

        const { email } = currentUser;
        locals.currentUser = { email }
    }

    return resolve(event);
};
