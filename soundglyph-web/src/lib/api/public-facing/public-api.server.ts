import { ApiClient } from '../utils/client.server';

export class PublicApi {
	private apiClient: ApiClient;
	private domain: string;

	private constructor(domain: string) {
		this.domain = domain;

		this.apiClient = new ApiClient([
			(input, init) => {
				const headers = new Headers(init && init.headers ? init.headers : undefined);

				return { headers, ...init };
			}
		]);
	}

	public static getInstance(domain: string) {
		return new PublicApi(domain);
	}
}
