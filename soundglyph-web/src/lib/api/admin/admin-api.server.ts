import { ApiClient } from "../utils/client.server";
import { UserMeApi } from "./resources/current-user/me-api";

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
}