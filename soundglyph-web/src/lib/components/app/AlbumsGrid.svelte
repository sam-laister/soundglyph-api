<script lang="ts">
	import { Badge } from '$lib/components/ui/badge';
	import type { AlbumBasicResponse } from '@/api/admin/resources/albums-api';
	import AlbumOverview from './AlbumOverview.svelte';
	import NewAlbumButton from './ NewAlbumButton.svelte';
	export let albums: AlbumBasicResponse[] = [];
	export let selectAlbum: (album: any) => void;
	export let onCreateAlbum: () => void;
</script>

<div class="container mx-auto rounded-2xl p-6">
	<div class="mb-8 flex w-full items-center justify-between">
		<h1 class="text-foreground text-4xl font-bold">Library</h1>
		<Badge variant="secondary" class="border-gray/10">
			{albums.length} Albums
		</Badge>
	</div>
	<div
		class="border-gray/10 grid grid-cols-2 gap-6 rounded-2xl border p-6 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6"
	>
		{#each albums as album}
			<AlbumOverview {album} {selectAlbum} />
		{/each}
		<NewAlbumButton {onCreateAlbum} />
	</div>
</div>
