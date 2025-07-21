import { writable } from 'svelte/store';
import type { LoginResponse } from '$lib/types/auth';
import type { UserBasic } from '@/types/user';

interface AuthState {
	isAuthenticated: boolean;
	user: UserBasic | null;
	token: string | null;
	loading: boolean;
	error: string | null;
}

const initialState: AuthState = {
	isAuthenticated: false,
	user: null,
	token: null,
	loading: false,
	error: null
};

export const authStore = writable<AuthState>(initialState);

export const authActions = {
	setLoading: (loading: boolean) => {
		authStore.update((state) => ({ ...state, loading, error: null }));
	},

	setError: (error: string) => {
		authStore.update((state) => ({ ...state, error, loading: false }));
	},

	setAuth: (token: string) => {
		authStore.update((state) => ({
			...state,
			token
		}));
	},

	login: (user: UserBasic) => {
		authStore.update((state) => ({
			...state,
			isAuthenticated: true,
			user,
			loading: false,
			error: null
		}));
	},

	logout: () => {
		authStore.set(initialState);
	},

	clearError: () => {
		authStore.update((state) => ({ ...state, error: null }));
	}
};
