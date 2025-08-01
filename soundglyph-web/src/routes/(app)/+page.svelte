<script lang="ts">
	import AlbumsGrid from '$lib/components/app/AlbumsGrid.svelte';
	import { goto } from '$app/navigation';
	import { setCurrentTrack } from '$lib/stores/player';
	import { onMount } from 'svelte';
	import type { AlbumBasicResponse } from '@/api/admin/resources/albums-api';

	let albums: AlbumBasicResponse[] = $state([]);

	onMount(async () => {
		const response = await fetch('/api/albums');
		const data = await response.json();
		albums = data;
	});

	function selectAlbum(album: any) {
		const track = album.tracks[0];
		setCurrentTrack(track, album);

		goto(`/album/${album.id}`);
	}
</script>

<AlbumsGrid {albums} {selectAlbum} />
