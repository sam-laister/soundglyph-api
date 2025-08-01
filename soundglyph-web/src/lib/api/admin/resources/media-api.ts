import { PUBLIC_API_URL } from "$env/static/public";
import type { ObjectData } from "@/api/utils/client.server";

export interface MediaBasicResponse extends ObjectData {
    id: string;
    path: string;
}

export function getMediaUrl(id: string) {
    return `/api/media/${id}`;
}