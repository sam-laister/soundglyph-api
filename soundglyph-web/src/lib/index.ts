// place files you want to import through the `$lib` alias in this folder.

export * from './types/auth';
export * from './stores/auth';

import type { Cookies } from "@sveltejs/kit";

export function redirectToLogin(url: URL, cookies: Cookies) {
    const loginUrl = new URL('/auth/login', url);

    if (url.pathname && url.pathname !== '/') {
        loginUrl.searchParams.set('goto', url.pathname);

        cookies.set('login_redirect', url.pathname.toString(), {
            path: '/',
            maxAge: 60 * 60 * 3 // 3 minute
        })
    }

    // redirect(303, '/auth/login');
    return Response.redirect(loginUrl.toString());
}