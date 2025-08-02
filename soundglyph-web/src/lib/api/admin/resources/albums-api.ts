import type { ObjectData, ApiClient, QueryOptions, CollectionResponse, GenericResponse } from "@/api/utils/client.server";
import type { TrackBasicResponse } from "./tracks-api";
import type { ArtistBasicResponse } from "./artist-api";
import type { MediaBasicResponse } from "./media-api";


// Models
export interface CreateAlbumInput {
    title: string;
    artist: string[];
    artwork?: string;
}

export interface UpdateAlbumInput {
    title: string;
    file?: File;
}

export interface AlbumBasicResponse extends ObjectData {
    id: string;
    tracks: TrackBasicResponse[];
    artist: ArtistBasicResponse[];
    artwork: MediaBasicResponse;
    title: string;
}

export interface AlbumsComplexResponse extends AlbumBasicResponse {
    tracks: TrackBasicResponse[];
}

export class AlbumsApi {
    constructor(private apiClient: ApiClient) { }

    /**
     * Get albums
     */
    async getAlbums(options: QueryOptions) {
        return await this.apiClient.query('/albums', options) as CollectionResponse<AlbumBasicResponse>;
    }

    /**
     * Create a new album
     */
    async createAlbum(input: CreateAlbumInput) {
        const formData = new FormData();
        formData.append('title', input.title);
        formData.append('artist', input.artist.join(','));
        if (input.artwork) {
            formData.append('artwork', input.artwork);
        }

        console.log(formData);

        return await this.apiClient.fetchPOSTFormData('/albums', formData) as GenericResponse<AlbumBasicResponse>;
    }

    /**
     * Get album by ID
     */
    async getAlbumById(id: string) {
        return await this.apiClient.fetchGET(`/albums/${id}`) as GenericResponse<AlbumBasicResponse>;
    }

    /**
     * Update album
     */
    async updateAlbum(id: number, input: Partial<UpdateAlbumInput>) {
        return await this.apiClient.fetchPATCH(`/albums/${id}`, input) as GenericResponse<AlbumBasicResponse>;
    }

    /**
     * Delete album by ID
     */
    async deleteAlbum(id: number) {
        return await this.apiClient.fetchDELETE(`/albums/${id}`) as GenericResponse<AlbumBasicResponse>;
    }

    /**
     * Get album tracks by ID
     */
    async getAlbumTracks(id: number) {
        return await this.apiClient.fetchGET(`/albums/${id}/tracks`) as GenericResponse<TrackBasicResponse>;
    }
}