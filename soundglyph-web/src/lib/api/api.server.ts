import { AuthenticationApi } from './admin/auth-api.server';
import { AdminApi } from './admin/admin-api.server';
import { PublicApi } from './public-facing/public-api.server';

function authApi() {
	return new AuthenticationApi();
}

function publicApi(domain: string) {
	return PublicApi.getInstance(domain);
}

function adminApi(accessToken: string) {
	return AdminApi.getInstance(accessToken);
}

export { authApi, publicApi, adminApi };
