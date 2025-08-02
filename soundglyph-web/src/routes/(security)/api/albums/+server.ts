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

export const POST: RequestHandler = async ({ locals, request }) => {
    try {
        const api = adminApi(locals.accessToken ?? "");

        const formData = await request.formData();
        const title = formData.get('title');
        const artist = formData.get('artist');
        const artwork = formData.get('artwork');

        const album = await api.albums().createAlbum({
            title: title as string,
            artist: [artist as string],
            // artwork: artwork as string
        });

        return json(album);
    } catch (error) {
        console.error('Error creating album:', error);

        return json({
            success: false,
            error: 'Failed to create album'
        }, { status: 500 });
    }
};