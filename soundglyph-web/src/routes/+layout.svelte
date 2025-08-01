<script lang="ts">
	import '../app.css';

	let { children } = $props();
</script>

<!-- Overlay patterns -->
<!-- <div class="absolute inset-0 opacity-30">
	<div
		class="absolute top-0 left-0 h-96 w-96 animate-pulse rounded-full bg-white mix-blend-overlay blur-3xl filter"
	></div>
	<div
		class="absolute right-0 bottom-0 h-96 w-96 animate-pulse rounded-full bg-red-300 mix-blend-overlay blur-3xl filter"
	></div>
</div> -->

<div
	class="animate-gradient bg-gradient-to-white absolute inset-0 from-blue-100 via-indigo-200 to-red-100 bg-[length:400%_400%]"
>
	{@render children()}
</div>
