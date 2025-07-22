// See https://svelte.dev/docs/kit/types#app.d.ts
// for information about these interfaces

import type { User } from "@/api/models";

declare global {
	namespace App {
		interface Locals {
			accessToken: string;
			currentUser?: User;
		}

		// interface Error {}
		// interface Locals {}
		// interface PageData {}
		// interface PageState {}
		// interface Platform {}
	}
}

export { };
