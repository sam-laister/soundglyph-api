import { apiErrors, type ApiError } from './error.svelte';
import { PUBLIC_API_URL } from '$env/static/public';
import { toast } from 'svelte-sonner';

export interface GenericResponse<T = any> {
	response: Response;
	data: T;
	ok: boolean;
	status: number;
	statusText: string;
}

export interface ObjectData {
	'@type': string;
}

export interface CollectionData<T = any> {
	'@type': string;
	total_items: number;
	member?: T[];
}

export interface CollectionResponse<T = any> extends GenericResponse<CollectionData<T>> {
	total_items: number;
	items: T[];
}

export interface QueryOptions {
	limit?: number;
	page?: number;
	query?: string;
	sort?: string;
	sortDirection?: 'asc' | 'desc';
	params?: any;
}

export function downloadFile(blob: any, downloadName: string) {
	const url = URL.createObjectURL(blob);
	const a = document.createElement('a');

	a.href = url;
	a.download = downloadName; // Set the desired file name

	document.body.appendChild(a);
	a.click();

	document.body.removeChild(a);
	URL.revokeObjectURL(url);
}

export function boolToString(bool?: boolean) {
	if (bool === undefined) {
		return undefined;
	}

	return bool ? 'true' : 'false';
}

export type MiddlewareCallback = (input?: URL | RequestInfo, init?: RequestInit) => RequestInit;

export class ApiClient {
	middleware: MiddlewareCallback[] = [];

	constructor(middleware: MiddlewareCallback[] = []) {
		this.middleware = middleware ?? [];
	}

	private getUrl(resourcePath: string, searchParams?: URLSearchParams) {
		if (!resourcePath.startsWith('/')) {
			resourcePath = '/' + resourcePath;
		}

		const url = new URL(resourcePath, PUBLIC_API_URL);

		if (searchParams !== undefined) {
			searchParams.forEach((value, key) => {
				url.searchParams.set(key, value);
			});
		}

		return url;
	}

	private async authenticatedResponse(
		input: URL | RequestInfo,
		init?: RequestInit
	): Promise<Response | undefined> {
		const headers = new Headers(init && init.headers ? init.headers : undefined);

		if (init === undefined) {
			init = {
				method: 'GET'
			};
		}

		console.log(`${init?.method ?? 'GET'} ${input.toString()}`);

		if (!headers.get('Accept')) {
			headers.set('Accept', 'application/ld+json');
		}

		init = { ...init, headers };

		for (const m of this.middleware) {
			init = m(input, init);
		}

		try {
			return await fetch(input, init);
		} catch (exception) {
			throw exception;
		}
	}

	private async jsonResponse(input: URL | RequestInfo, init?: any): Promise<GenericResponse> {
		let response: Response | undefined = undefined;

		try {
			response = await this.authenticatedResponse(input, init);

			if (!response || !response?.ok) {
				console.log(response);
			}

			const data = await response?.json();

			return {
				ok: response?.ok ?? false,
				status: response?.status ?? 500,
				statusText: response?.statusText ?? 'No response',
				response,
				data
			};
		} catch (exception) {
			console.log(exception);

			let data;

			try {
				data = JSON.parse((await response?.text()) ?? '') as ApiError;
			} catch (e) {
				data = undefined;
			}

			if (response?.status === 403) {
				toast.error('Access denied');
			} else if (response?.status === 404) {
				toast.error('Not found');
			} else {
				toast.error('An API error occurred');
			}

			// Add error to display modal
			apiErrors.apiErrors.push({
				title: data?.title ?? '',
				description: data?.description ?? ''
				// @ts-ignore
				// debugTokenLink: response?.headers.get('X-Debug-Token-Link'),
				// trace: data?.trace,
			});

			if (response) {
				return {
					ok: false,
					status: response.status,
					statusText: response.statusText,
					response,
					data: undefined
				};
			} else {
				return {
					ok: false,
					status: 500,
					statusText: 'Unknown error',
					data: response
				};
			}
		}
	}

	async fetchGET(path: string, queryParams: object = {}) {
		const url = this.getUrl(path);

		for (const [key, value] of Object.entries(queryParams)) {
			url.searchParams.set(key, value);
		}

		return await this.jsonResponse(url, {
			method: 'GET',
			headers: {
				Accept: 'application/ld+json'
			}
		});
	}

	async fetchPOST(path: string, data: object = {}, searchParams?: URLSearchParams) {
		const url = this.getUrl(path, searchParams);

		return await this.jsonResponse(url, {
			method: 'POST',
			body: JSON.stringify(data),
			headers: {
				'Content-Type': 'application/ld+json',
				Accept: 'application/ld+json'
			}
		});
	}

	async fetchPOSTFormData(path: string, data: FormData, searchParams?: URLSearchParams) {
		const url = this.getUrl(path, searchParams);
		return await this.jsonResponse(url, {
			method: 'POST',
			body: data,
			headers: {
				'Content-Type': 'multipart/form-data',
				Accept: 'application/ld+json'
			}
		});
	}

	async fetchPUT(path: string, data: object, searchParams?: URLSearchParams) {
		const url = this.getUrl(path, searchParams);

		return await this.jsonResponse(url, {
			method: 'PUT',
			body: JSON.stringify(data),
			headers: {
				'Content-Type': 'application/ld+json',
				Accept: 'application/ld+json'
			}
		});
	}

	async fetchPATCH(path: string, data: object, searchParams?: URLSearchParams) {
		const url = this.getUrl(path, searchParams);

		return await this.jsonResponse(url, {
			method: 'PATCH',
			body: JSON.stringify(data),
			headers: {
				'Content-Type': 'application/ld+json',
				Accept: 'application/ld+json'
			}
		});
	}

	async fetchDELETE(path: string, searchParams?: URLSearchParams) {
		const url = this.getUrl(path, searchParams);

		return await this.jsonResponse(url, {
			method: 'DELETE',
			headers: {
				Accept: 'application/ld+json'
			}
		});
	}

	public async query(resourceUrlPath: string, options: QueryOptions = {}) {
		// Defaults
		options.limit = options.limit ?? 20;
		options.page = options.page ?? 1;
		options.query = options.query;

		const url = this.getUrl(resourceUrlPath);

		for (let [param, value] of Object.entries(options.params ?? {})) {
			if (value === undefined) {
				continue;
			}

			// Booleans
			if (typeof value == 'boolean') {
				value = boolToString(value);
			}

			if (Array.isArray(value)) {
				for (const v of value) {
					url.searchParams.append(param, v as string);
				}
			} else {
				url.searchParams.append(param, value as string);
			}
		}

		const response = await this.jsonResponse(url, {
			method: 'GET',
			headers: {
				Accept: 'application/ld+json'
			}
		});

		const data: CollectionData = response.data;

		return {
			// JSON response (data, ok, status, etc.)
			...response,

			// Collection specific
			total_items: data.total_items,
			items: data.member
		} as CollectionResponse;
	}

	public async getFileBlob(url: string, mimeType: string) {
		const headers = new Headers();

		headers.set('Accept', mimeType);

		const response = await this.authenticatedResponse(url, {
			headers: headers
		});

		return await response?.blob();
	}
}
