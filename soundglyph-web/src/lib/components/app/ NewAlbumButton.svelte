<script lang="ts">
	import { Plus } from '@lucide/svelte';

	export let onCreateAlbum: () => void;
</script>

<button
	type="button"
	class="group aspect-square cursor-pointer transition-all duration-300 hover:scale-105"
	onclick={onCreateAlbum}
	onkeydown={(e) => e.key === 'Enter' && onCreateAlbum()}
>
	<div
		class="border-border bg-background hover:border-primary/30 relative overflow-hidden rounded-xl border backdrop-blur-sm transition-all duration-300"
	>
		<div class="relative aspect-square overflow-hidden">
			<div
				class="group-hover:from-primary/10 group-hover:to-primary/20 flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 transition-all duration-300 dark:from-gray-800 dark:to-gray-900"
			>
				<Plus
					class="group-hover:text-primary h-12 w-12 text-gray-400 transition-all duration-300 group-hover:scale-110"
				/>
			</div>
		</div>
		<div class="p-4">
			<h3 class="text-foreground mb-1 truncate font-semibold">Create New Album</h3>
			<p class="truncate text-sm text-gray-400">Add a new album to your library</p>
		</div>
	</div>
</button>
