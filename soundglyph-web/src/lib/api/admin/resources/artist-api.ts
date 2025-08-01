import type { ObjectData, ApiClient, QueryOptions, CollectionResponse, GenericResponse } from "@/api/utils/client.server";
import type { AlbumBasicResponse } from "./albums-api";


// Models
export interface UpdateArtistInput {
    name: string;
    file: string;
}

export interface ArtistBasicResponse extends ObjectData {
    id: string;
    name: string;
    artwork: string;
}

export class ArtistsApi {
    constructor(private apiClient: ApiClient) { }

    /**
     * Get albums
     */
    async getArtists(options: QueryOptions) {
        return await this.apiClient.query('/artists', options) as CollectionResponse<ArtistBasicResponse>;
    }

    /**
     * Create a new album
     */
    async createArtist(input: UpdateArtistInput) {
        return await this.apiClient.fetchPOST('/artists', input) as GenericResponse<ArtistBasicResponse>;
    }

    /**
     * Get album by ID
     */
    async getArtistById(id: number) {
        return await this.apiClient.fetchGET(`/artists/${id}`) as GenericResponse<ArtistBasicResponse>;
    }

    /**
     * Update album
     */
    async updateArtist(id: number, input: Partial<UpdateArtistInput>) {
        return await this.apiClient.fetchPATCH(`/artists/${id}`, input) as GenericResponse<ArtistBasicResponse>;
    }

    /**
     * Delete album by ID
     */
    async deleteArtist(id: number) {
        return await this.apiClient.fetchDELETE(`/artists/${id}`) as GenericResponse<ArtistBasicResponse>;
    }

    /**
     * Get album tracks by ID
     */
    async getArtistAlbums(id: number) {
        return await this.apiClient.fetchGET(`/artists/${id}/albums`) as GenericResponse<AlbumBasicResponse>;
    }
}