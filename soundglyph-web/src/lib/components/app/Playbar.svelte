<script lang="ts">
	import { Pause, Play, Heart, Volume2 } from '@lucide/svelte';
	import Button from '../ui/button/button.svelte';
	import { getMediaUrl } from '@/api/admin/resources/media-api';
	import type { TrackBasicResponse } from '@/api/admin/resources/tracks-api';
	export let currentTrack: TrackBasicResponse;
	export let isPlaying: boolean;
	export let togglePlay: () => void;
	export let currentTime: number;
	export let formatTime: (seconds: number) => string;
</script>

{#if currentTrack}
	<div class="border-t border-white/10 bg-black/40 backdrop-blur-md">
		<div class="container mx-auto px-6 py-4">
			<!-- Progress Bar -->
			<div class="mb-4 h-1 w-full rounded-full bg-white/20">
				<div
					class="h-1 rounded-full bg-purple-500 transition-all duration-1000"
					style="width: {currentTime > 0
						? (currentTime / currentTrack.duration) * 100
						: 0}%"
				></div>
			</div>
			<div class="flex items-center justify-between">
				<!-- Track Info -->
				<div class="flex flex-1 items-center gap-4">
					<img
						src={currentTrack?.artwork
							? getMediaUrl(currentTrack.artwork.id)
							: '/placeholder.svg'}
						alt={currentTrack?.title}
						class="h-12 w-12 rounded-lg object-cover"
					/>
					<div class="min-w-0">
						<h4 class="truncate font-medium text-white">{currentTrack.title}</h4>
						<p class="truncate text-sm text-gray-400">
							{currentTrack?.artist?.map((artist) => artist.name).join(', ')}
						</p>
					</div>
					<Button
						variant="ghost"
						size="sm"
						class="text-white hover:bg-white/10"
						onkeydown={(e) => e.key === 'Enter' && console.log('Like track')}
						role="button"
					>
						<Heart class="h-4 w-4" />
					</Button>
				</div>
				<!-- Controls -->
				<div class="flex items-center gap-2">
					<Button
						type="button"
						variant="ghost"
						size="sm"
						class="text-white hover:bg-white/10"
						onclick={togglePlay}
						onkeydown={(e) => e.key === 'Enter' && togglePlay()}
						role="button"
					>
						{#if isPlaying}
							<Pause class="h-5 w-5" />
						{:else}
							<Play class="h-5 w-5" />
						{/if}
					</Button>
				</div>
				<!-- Volume & Time -->
				<div class="flex-2 flex items-center justify-end gap-4">
					<span class="text-sm text-white">
						{formatTime(currentTime)} / {formatTime(currentTrack.duration)}
					</span>
					<div class="flex items-center gap-2">
						<Volume2 class="h-4 w-4 text-gray-400" />
						<div class="h-1 w-20 rounded-full bg-white/20">
							<div class="h-1 w-3/4 rounded-full bg-white"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{/if}
