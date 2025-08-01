import { ApiClient } from "../utils/client.server";
import { AlbumsApi } from "./resources/albums-api";
import { ArtistsApi } from "./resources/artist-api";
import { UserMeApi } from "./resources/current-user/me-api";
import { TracksApi } from "./resources/tracks-api";

export class AdminApi {
    private apiClient: ApiClient;

    private constructor(accessToken: string) {
        this.apiClient = new ApiClient([
            // Authentication
            (input, init) => {
                const headers = new Headers(init && init.headers ? init.headers : undefined);

                headers.set('Authorization', `Bearer ${accessToken}`);

                return { ...init, headers }
            }
        ]);
    }

    public static getInstance(accessToken: string) {
        return new AdminApi(accessToken);
    }

    public currentUser() {
        return new UserMeApi(this.apiClient);
    }

    public albums() {
        return new AlbumsApi(this.apiClient);
    }

    public artists() {
        return new ArtistsApi(this.apiClient);
    }

    public tracks() {
        return new TracksApi(this.apiClient);
    }
}