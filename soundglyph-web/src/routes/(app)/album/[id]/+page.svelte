<script lang="ts">
	import AlbumDetail from '$lib/components/app/AlbumDetail.svelte';
	import { goto } from '$app/navigation';
	import { Progress } from '$lib/components/ui/progress';
	import { onMount } from 'svelte';
	import { setCurrentTrack } from '$lib/stores/player';
	import type { AlbumsComplexResponse } from '@/api/admin/resources/albums-api';
	import { page } from '$app/state';

	let album: AlbumsComplexResponse | null = $state(null);
	let loading = $state(false);
	let value = $state(0);

	const id = page.params.id;

	async function loadAlbum(id: string) {
		loading = true;
		album = null;
		album = await fetchAlbumById(id);
		loading = false;
	}

	onMount(() => {
		loadAlbum(id);

		const timer = setTimeout(() => (value = 66), 100);
		return () => clearTimeout(timer);
	});

	async function fetchAlbumById(id: string) {
		const response = await fetch(`/api/albums/${id}`);
		const data = await response.json();
		return data as AlbumsComplexResponse;
	}

	function goBack() {
		goto('/');
	}

	function playTrack(track: any, album: any) {
		setCurrentTrack(track, album);
	}
</script>

{#if loading}
	<div class="flex h-screen items-center justify-center">
		<Progress {value} max={100} class="w-[60%]" />
	</div>
{:else if album}
	<div class="flex items-center justify-center">
		<AlbumDetail selectedAlbum={album} {goBack} {playTrack} />
	</div>
{/if}
