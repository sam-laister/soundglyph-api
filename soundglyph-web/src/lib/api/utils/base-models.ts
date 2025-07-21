export type UUID = string;

export interface MessageResponse {
    message: string;
}

export interface ErrorResponse {
    error: string;
}

export interface BaseModel {
    id: UUID;
    createdAt: string;
    updatedAt: string;
}

export interface Address {
    city?: string;
    country?: string;
    line1?: string;
    line2?: string;
    postal_code?: string;
    state?: string;
}