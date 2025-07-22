<script lang="ts">
	import { goto } from '$app/navigation';
	import { page } from '$app/state';

	// Define your tab routes
	const tabs = [
		{ label: 'Analytics', path: '/analytics' },
		{ label: 'Home', path: '/' },
		{ label: 'Settings', path: '/settings' }
	];

	// Get the current path to highlight the active tab
	$: currentPath = page.url.pathname;
</script>

<div class="border-b border-white/10 bg-black/20 backdrop-blur-sm">
	<div class="container mx-auto px-6">
		<div class="grid w-full max-w-md grid-cols-3 border border-white/10 bg-white/5">
			{#each tabs as tab}
				<button
					class="px-4 py-2 text-white transition-colors
              {currentPath === tab.path ? 'bg-white/20 text-white' : ''}"
					on:click={() => goto(tab.path)}
					aria-current={currentPath === tab.path ? 'page' : undefined}
				>
					{tab.label}
				</button>
			{/each}
		</div>
	</div>
</div>
