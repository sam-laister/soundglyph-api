import { adminApi } from '@/api/api.server';
import { json, type RequestHandler } from '@sveltejs/kit';

export const GET: RequestHandler = async ({ locals }) => {
    try {
        const api = adminApi(locals.accessToken ?? "");

        const { items: albums } = await api.albums().getAlbums({
            limit: 100,
            page: 1
        });

        return json(albums);
    } catch (error) {
        console.error('Error retrieving menu:', error);

        return json({
            success: false,
            error: 'Failed to retrieve menu information'
        }, { status: 500 });
    }
};