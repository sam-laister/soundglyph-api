import type { ApiClient, GenericResponse, ObjectData } from "@/api/utils/client.server";

export interface UserMe extends ObjectData {
    email: string;
    id: string;
}

export class UserMeApi {
    constructor(private apiClient: ApiClient) { }

    /**
     * Retrieve the current user
     */
    async me() {
        return await this.apiClient.fetchGET('/me') as GenericResponse<UserMe>;
    }

    /**
     * Retrieve the current user
     */
    async updateMe(user: Partial<UserMe>) {
        return await this.apiClient.fetchPATCH('/me', user) as GenericResponse<UserMe>;
    }
}