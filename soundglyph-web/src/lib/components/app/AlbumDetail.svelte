<script lang="ts">
	import { ArrowLeft, Play, Heart, MoreHorizontal } from '@lucide/svelte';
	import Button from '../ui/button/button.svelte';
	import type { AlbumsComplexResponse } from '@/api/admin/resources/albums-api';
	import { getMediaUrl } from '@/api/admin/resources/media-api';
	import { formatTime } from '@/stores/player';
	export let selectedAlbum: AlbumsComplexResponse;
	export let goBack: () => void;
	export let playTrack: (track: any, album: any) => void;
</script>

<div class="container rounded-2xl border border-white/10 bg-white/10 p-6">
	<div class="w-full">
		<Button
			type="button"
			variant="ghost"
			onclick={goBack}
			onkeydown={(e) => e.key === 'Enter' && goBack()}
			class="text-foreground hover:bg-primary/10 mb-6 cursor-pointer"
			role="button"
		>
			<ArrowLeft class="mr-2 h-4 w-4" />
			Back to Albums
		</Button>
	</div>
	<div class="border-gray/10 grid grid-cols-1 gap-8 rounded-2xl border p-6 lg:grid-cols-3">
		<!-- Album Art -->
		<div class="lg:col-span-1">
			<div class="sticky top-6">
				<div class="aspect-square overflow-hidden rounded-2xl shadow-2xl">
					<img
						src={getMediaUrl(selectedAlbum.artwork.id) || '/placeholder.svg'}
						alt={selectedAlbum.title}
						class="h-full w-full object-cover"
					/>
				</div>
				<div class="mt-6 text-center">
					<h1 class="text-foreground mb-2 text-3xl font-bold">
						{selectedAlbum.title}
					</h1>
					<p class="mb-1 text-xl text-gray-300">
						{selectedAlbum.artist.map((artist) => artist.name).join(', ')}
					</p>
					<p class="text-sm text-gray-500">
						{selectedAlbum?.tracks.length} tracks
					</p>
				</div>
			</div>
		</div>
		<!-- Track List -->
		<div class="lg:col-span-2">
			<div
				class="cursor-pointer overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm"
			>
				<div class="border-b border-white/10 p-6">
					<div class="flex items-center gap-4">
						<Button
							type="button"
							size="lg"
							class="h-14 w-14 rounded-full bg-purple-600 text-white hover:bg-purple-700"
							onclick={() => playTrack(selectedAlbum.tracks[0], selectedAlbum)}
							onkeydown={(e) =>
								e.key === 'Enter' &&
								playTrack(selectedAlbum.tracks[0], selectedAlbum)}
							role="button"
						>
							<Play class="h-6 w-6" />
						</Button>
						<div>
							<h2 class="text-foreground text-xl font-semibold">Play Album</h2>
							<p class="text-sm text-gray-400">Start from the beginning</p>
						</div>
					</div>
				</div>
				<div class="divide-border divide-y">
					{#each selectedAlbum.tracks as track, index}
						<!-- svelte-ignore a11y_interactive_supports_focus -->
						<div
							class="group flex cursor-pointer items-center gap-4 p-4 transition-colors hover:bg-white/5"
							onclick={() => playTrack(track, selectedAlbum)}
							onkeydown={(e) => e.key === 'Enter' && playTrack(track, selectedAlbum)}
							role="button"
						>
							<div class="w-8 text-center">
								<Play
									class="text-foreground mx-auto hidden h-4 w-4 group-hover:block"
								/>
							</div>
							<div class="flex-1">
								<h4
									class="text-foreground font-medium transition-colors group-hover:text-purple-400"
								>
									{track.title}
								</h4>
								<p class="text-sm text-gray-400">
									{selectedAlbum?.artist.map((artist) => artist.name).join(', ')}
								</p>
							</div>
							<div class="flex items-center gap-2">
								<Button
									variant="ghost"
									size="sm"
									class="opacity-0 transition-opacity group-hover:opacity-100"
									onkeydown={(e) =>
										e.key === 'Enter' && console.log('Like track')}
									role="button"
								>
									<Heart class="h-4 w-4" />
								</Button>
								<span class="w-12 text-right text-sm text-gray-400"
									>{formatTime(track.duration)}</span
								>
								<Button
									variant="ghost"
									size="sm"
									class="opacity-0 transition-opacity group-hover:opacity-100"
									onkeydown={(e) =>
										e.key === 'Enter' && console.log('More options')}
									role="button"
								>
									<MoreHorizontal class="h-4 w-4" />
								</Button>
							</div>
						</div>
					{/each}
				</div>
			</div>
		</div>
	</div>
</div>
