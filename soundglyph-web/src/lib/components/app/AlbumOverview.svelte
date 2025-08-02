<script lang="ts">
	import { getMediaUrl } from '@/api/admin/resources/media-api';
	import { Play } from '@lucide/svelte';
	import type { AlbumBasicResponse } from '@/api/admin/resources/albums-api';

	export let album: AlbumBasicResponse;
	export let selectAlbum: (album: AlbumBasicResponse) => void;
</script>

<button
	type="button"
	class="group aspect-square cursor-pointer transition-all duration-300 hover:scale-105"
	onclick={() => selectAlbum(album)}
	onkeydown={(e) => e.key === 'Enter' && selectAlbum(album)}
>
	<div
		class="border-border bg-background hover:border-primary/30 relative overflow-hidden rounded-xl border backdrop-blur-sm transition-all duration-300"
	>
		<div class="relative aspect-square overflow-hidden">
			{#if album.artwork}
				<img
					src={getMediaUrl(album.artwork.id) || '/placeholder.svg'}
					alt={album.title}
					class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
				/>
			{/if}
			<div
				class="absolute inset-0 flex items-center justify-center bg-black/0 transition-all duration-300 group-hover:bg-black/20"
			>
				<Play
					class="h-12 w-12 text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100"
				/>
			</div>
		</div>
		<div class="p-4">
			<h3 class="text-foreground mb-1 truncate font-semibold">
				{album.title}
			</h3>
			<p class="truncate text-sm text-gray-400">
				{album.artist.map((artist) => artist.name).join(', ')}
			</p>
			<p class="mt-1 text-xs text-gray-500">
				{album.tracks.length} tracks
			</p>
		</div>
	</div>
</button>
