export interface LoginRequest {
	email: string;
	password: string;
}

export interface LoginResponse {
	token?: string;
}

export interface ApiError {
	error: string;
}
