<script lang="ts">
	import { onNavigate } from '$app/navigation';
	import { onMount, setContext, type Snippet } from 'svelte';
	import { authState } from '@/api/auth.client.svelte';
	import type { LayoutData } from './$types';
	import Playbar from '$lib/components/app/Playbar.svelte';
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
</div>
