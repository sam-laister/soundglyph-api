<script lang="ts">
	import { Badge } from '$lib/components/ui/badge';
	import { Play } from '@lucide/svelte';
	export let albums: any[] = [];
	export let selectAlbum: (album: any) => void;
</script>

<div class="container mx-auto rounded-2xl border border-white/10 bg-white/10 p-6">
	<div class="mb-8 flex w-full items-center justify-between">
		<h1 class="text-4xl font-bold text-foreground">Your Music</h1>
		<Badge variant="secondary" class="border-white/20 bg-white/10 text-white">
			{albums.length} Albums
		</Badge>
	</div>
	<div
		class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6"
	>
		{#each albums as album}
			<button
				type="button"
				class="group cursor-pointer transition-all duration-300 hover:scale-105"
				onclick={() => selectAlbum(album)}
				onkeydown={(e) => e.key === 'Enter' && selectAlbum(album)}
			>
				<div
					class="relative overflow-hidden rounded-xl border border-border bg-background backdrop-blur-sm transition-all duration-300 hover:border-primary/30"
				>
					<div class="relative aspect-square overflow-hidden">
						<img
							src={album.cover || '/placeholder.svg'}
							alt={album.title}
							class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
						/>
						<div
							class="absolute inset-0 flex items-center justify-center bg-black/0 transition-all duration-300 group-hover:bg-black/20"
						>
							<Play
								class="h-12 w-12 text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100"
							/>
						</div>
					</div>
					<div class="p-4">
						<h3 class="mb-1 truncate font-semibold text-foreground">
							{album.title}
						</h3>
						<p class="truncate text-sm text-gray-400">
							{album.artist}
						</p>
						<p class="mt-1 text-xs text-gray-500">
							{album.year}
						</p>
					</div>
				</div>
			</button>
		{/each}
	</div>
</div>
