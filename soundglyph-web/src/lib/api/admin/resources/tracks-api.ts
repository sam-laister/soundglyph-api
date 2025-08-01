import type { ObjectData, ApiClient, QueryOptions, CollectionResponse, GenericResponse } from "@/api/utils/client.server";
import type { AlbumBasicResponse, UpdateAlbumInput } from "./albums-api";
import type { ArtistBasicResponse } from "./artist-api";
import type { MediaBasicResponse } from "./media-api";


// Models
export interface UpdateTrackInput {
    title: string;
    duration: number;
    track: number;
    albumId: string;
}

export interface TrackBasicResponse extends ObjectData {
    id: string;
    albums: AlbumBasicResponse[];
    artist: ArtistBasicResponse[];
    title: string;
    audio: MediaBasicResponse;
    artwork: MediaBasicResponse;
    duration: number;
}

export class TracksApi {
    constructor(private apiClient: ApiClient) { }

    /**
     * Get albums
     */
    async getTracks(options: QueryOptions) {
        return await this.apiClient.query('/tracks', options) as CollectionResponse<TrackBasicResponse>;
    }

    /**
     * Create a new album
     */
    async createTrack(input: UpdateTrackInput) {
        return await this.apiClient.fetchPOST('/tracks', input) as GenericResponse<TrackBasicResponse>;
    }

    /**
     * Get album by ID
     */
    async getTrackById(id: number) {
        return await this.apiClient.fetchGET(`/tracks/${id}`) as GenericResponse<TrackBasicResponse>;
    }

    /**
     * Update album
     */
    async updateTrack(id: number, input: Partial<UpdateTrackInput>) {
        return await this.apiClient.fetchPATCH(`/tracks/${id}`, input) as GenericResponse<TrackBasicResponse>;
    }

    /**
     * Delete album by ID
     */
    async deleteTrack(id: number) {
        return await this.apiClient.fetchDELETE(`/tracks/${id}`) as GenericResponse<TrackBasicResponse>;
    }

    /**
     * Get album tracks by ID
     */
    async getTrackAlbums(id: number) {
        return await this.apiClient.fetchGET(`/tracks/${id}/albums`) as GenericResponse<AlbumBasicResponse>;
    }
}