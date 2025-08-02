<script lang="ts">
	import { onNavigate } from '$app/navigation';
	import { page } from '$app/stores';
	import { goto } from '$app/navigation';
	import { onMount, setContext, type Snippet } from 'svelte';
	import { authState } from '@/api/auth.client.svelte';
	import type { LayoutData } from './$types';
	import Playbar from '$lib/components/app/Playbar.svelte';
	import CreateAlbumModal from '$lib/components/app/CreateAlbumModal.svelte';
	import {
		currentTrack,
		isPlaying,
		togglePlay,
		currentTime,
		formatTime
	} from '$lib/stores/player';

	interface ViewerLayoutProps {
		data: LayoutData;
		children: Snippet;
	}

	let { data, children }: ViewerLayoutProps = $props();

	const { user, tokenExpiration } = data;

	// Set context
	setContext('user', user);

	onNavigate((navigation) => {
		if (!document.startViewTransition) return;

		return new Promise((resolve) => {
			document.startViewTransition(async () => {
				resolve();
				await navigation.complete;
			});
		});
	});

	/*
	 * Auth modal
	 */
	authState.expiresAt = new Date(parseInt(tokenExpiration ?? '0'));

	$effect(() => {
		if (!authState.isAuthenticated) {
			authState.showLoginModal = true;
		}
	});

	onMount(() => {
		let loop: NodeJS.Timeout;

		const checkAuth = () => {
			if (authState.expiresAt && authState.expiresAt < new Date()) {
				authState.isAuthenticated = false;
			}

			clearTimeout(loop);
			loop = setInterval(checkAuth, 10000);
		};

		checkAuth();
	});

	// Shared state (if you want playbar to persist)
</script>

<div class="flex h-screen flex-col">
	<div class="flex-1 overflow-hidden">
		<div class="flex-1 overflow-auto">
			{@render children()}
		</div>
	</div>
	{#if $currentTrack}
		<Playbar
			currentTrack={$currentTrack}
			isPlaying={$isPlaying}
			{togglePlay}
			currentTime={$currentTime}
			{formatTime}
		/>
	{/if}
	{#if $page.url.searchParams.get('modal') === 'create-album'}
		<div
			class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
		>
			<div
				class="bg-background border-border relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-lg border p-6 shadow-lg"
			>
				<button
					onclick={() => goto($page.url.pathname)}
					class="absolute right-4 top-4 text-gray-400 hover:text-gray-600"
				>
					×
				</button>
				<CreateAlbumModal />
			</div>
		</div>
	{/if}
</div>
