<script lang="ts">
	import AlbumDetail from '$lib/components/app/AlbumDetail.svelte';
	import { goto } from '$app/navigation';
	import { Progress } from '$lib/components/ui/progress';
	import { onMount } from 'svelte';
	import { setCurrentTrack } from '$lib/stores/player';

	let album: any = $state(null);
	let loading = $state(false);
	let value = $state(0);

	async function loadAlbum(id: any) {
		loading = true;
		album = null;
		album = await fetchAlbumById(id);
		loading = false;
	}

	onMount(() => {
		loadAlbum(1);

		const timer = setTimeout(() => (value = 66), 500);
		return () => clearTimeout(timer);
	});

	// The fetchAlbumById function from above
	function fetchAlbumById(id: any) {
		return new Promise((resolve) => {
			setTimeout(() => {
				resolve({
					id,
					title: 'Simulated Album',
					artist: 'Simulated Artist',
					cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg',
					year: 2024,
					tracks: [
						{
							id: 1,
							title: 'Sim Track 1',
							duration: '3:42',
							track: 1,
							cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg'
						},
						{
							id: 2,
							title: 'Sim Track 2',
							duration: '4:15',
							track: 2,
							cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg'
						}
					]
				});
			}, 5);
		});
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
