import type { UserBasic } from '@/types/user';
import { ApiClient, type GenericResponse, type ObjectData } from '../utils/client.server';

// Models
export interface LoginInput {
	email: string;
	password: string;
}

export interface RegisterInput {
	email: string;
	password: string;
}

export interface LoginResponse {
	token: string;
}

export class AuthenticationApi {
	private apiClient: ApiClient;

	constructor() {
		this.apiClient = new ApiClient();
	}

	/**
	 * Authenticates a user with email and password and returns a JWT token
	 */
	async login(input: LoginInput) {
		const data = await this.apiClient.fetchPOST('/auth', input);
		return data as GenericResponse<LoginResponse>;
	}

	/**
	 * Creates a new user account with the provided information.
	 * Requires a valid signup code from POST /auth/start-signup.
	 */
	async register(input: RegisterInput) {
		return (await this.apiClient.fetchPOST('/users', input)) as GenericResponse<UserBasic>;
	}

	/**
	 * Gets current user
	 */
	async me() {
		return (await this.apiClient.fetchGET('/me')) as GenericResponse<UserBasic>;
	}
}
