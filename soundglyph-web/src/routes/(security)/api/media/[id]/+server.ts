import { error } from '@sveltejs/kit';
import type { RequestHandler } from './$types';
import { PUBLIC_API_URL } from '$env/static/public';
import { getMediaUrl } from '@/api/admin/resources/media-api';

export const GET: RequestHandler = async ({ params, locals, cookies, fetch }) => {
    const mediaId = params.id;
    
    if (!mediaId) {
        throw error(400, 'Media ID is required');
    }

    try {
        // Get the auth token from cookies
        const token = locals.accessToken;
        
        if (!token) {
            throw error(401, 'Authentication required');
        }

        // Fetch the media from your API with authentication
        const response = await fetch(`${PUBLIC_API_URL}/media-object?id=${mediaId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });

        if (!response.ok) {
            throw error(response.status, 'Failed to fetch media');
        }

        // Get the image data
        const imageBuffer = await response.arrayBuffer();
        
        // Determine content type from response headers
        const contentType = response.headers.get('content-type') || 'image/jpeg';

        return new Response(imageBuffer, {
            headers: {
                'Content-Type': contentType,
                'Cache-Control': 'public, max-age=31536000', // Cache for 1 year
                'Access-Control-Allow-Origin': '*'
            }
        });
    } catch (err) {
        console.error('Error fetching media:', err);
        throw error(500, 'Failed to fetch media');
    }
}; 