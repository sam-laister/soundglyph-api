import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ url, cookies }) => {
    const albums: any[] = [];

    return {
        albums
    }
};