import { error, json } from '@sveltejs/kit';
import type { RequestHandler } from './$types';
import { adminApi } from '@/api/api.server';

export const GET: RequestHandler = async ({ params, locals, cookies, fetch }) => {
    const albumId = params.id;

    try {
        const api = adminApi(locals.accessToken ?? "");
        const { data: album } = await api.albums().getAlbumById(albumId);

        return json(album);
    } catch (err) {
        console.error('Error fetching album:', err);
        throw error(500, 'Failed to fetch album');
    }
}; 