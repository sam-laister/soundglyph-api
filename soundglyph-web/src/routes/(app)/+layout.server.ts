import { adminApi } from '@/api/api.server';
import type { LayoutServerLoad } from './$types';

export const load: LayoutServerLoad = async ({ cookies, locals, url }) => {
    const accessToken = locals.accessToken;
    console.log('locals', locals);

    const api = adminApi(accessToken);

    // Token expiration
    const tokenExpiration = cookies.get('expiration');

    // User
    const currentUser = locals.currentUser;
    const { email } = currentUser;
    const user = { email }

    return {
        user,
        tokenExpiration
    };
};