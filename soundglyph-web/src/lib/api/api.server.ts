import { AuthenticationApi } from './admin/auth-api.server';
import { PublicApi } from './public-facing/public-api.server';

function authApi() {
	return new AuthenticationApi();
}

function publicApi(domain: string) {
	return PublicApi.getInstance(domain);
}

export { authApi, publicApi };
