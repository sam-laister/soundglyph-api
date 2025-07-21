export interface AuthUser {
	id: string;
	email: string;
	name?: string;
}

class AuthState {
	isAuthenticated = $state(true);
	expiresAt = $state(null as Date | null);
	showLoginModal = $state(false);
}

export const authState = new AuthState();

export function showAuthModal() {
	authState.showLoginModal = true;
}

export function closeAuthModal() {
	authState.showLoginModal = false;
}
