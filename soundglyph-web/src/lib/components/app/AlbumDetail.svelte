<script lang="ts">
	import { ArrowLeft, Play, Heart, MoreHorizontal } from '@lucide/svelte';
	import Button from '../ui/button/button.svelte';
	export let selectedAlbum: any;
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
			class="mb-6 cursor-pointer text-foreground hover:bg-primary/10"
			role="button"
		>
			<ArrowLeft class="mr-2 h-4 w-4" />
			Back to Albums
		</Button>
	</div>
	<div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
		<!-- Album Art -->
		<div class="lg:col-span-1">
			<div class="sticky top-6">
				<div class="aspect-square overflow-hidden rounded-2xl shadow-2xl">
					<img
						src={selectedAlbum?.cover || '/placeholder.svg'}
						alt={selectedAlbum?.title}
						class="h-full w-full object-cover"
					/>
				</div>
				<div class="mt-6 text-center">
					<h1 class="mb-2 text-3xl font-bold text-foreground">
						{selectedAlbum?.title}
					</h1>
					<p class="mb-1 text-xl text-gray-300">
						{selectedAlbum?.artist}
					</p>
					<p class="text-sm text-gray-500">
						{selectedAlbum?.year} • {selectedAlbum?.tracks.length} tracks
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
							<h2 class="text-xl font-semibold text-foreground">Play Album</h2>
							<p class="text-sm text-gray-400">Start from the beginning</p>
						</div>
					</div>
				</div>
				<div class="divide-y divide-border">
					{#each selectedAlbum.tracks as track, index}
						<!-- svelte-ignore a11y_interactive_supports_focus -->
						<div
							class="group flex cursor-pointer items-center gap-4 p-4 transition-colors hover:bg-white/5"
							onclick={() => playTrack(track, selectedAlbum)}
							onkeydown={(e) => e.key === 'Enter' && playTrack(track, selectedAlbum)}
							role="button"
						>
							<div class="w-8 text-center">
								<span class="text-gray-400 group-hover:hidden">{track.track}</span>
								<Play
									class="mx-auto hidden h-4 w-4 text-foreground group-hover:block"
								/>
							</div>
							<div class="flex-1">
								<h4
									class="font-medium text-foreground transition-colors group-hover:text-purple-400"
								>
									{track.title}
								</h4>
								<p class="text-sm text-gray-400">{selectedAlbum?.artist}</p>
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
									>{track.duration}</span
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
