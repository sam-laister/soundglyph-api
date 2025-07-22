<script lang="ts">
	import AnalyticsDashboard from '$lib/components/app/AnalyticsDashboard.svelte';

	export let data;

	const { albums } = data;
</script>

<AnalyticsDashboard {albums} />
