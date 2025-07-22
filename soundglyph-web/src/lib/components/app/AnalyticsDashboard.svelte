<script>
	import { Card, CardContent } from '../ui/card';

	const { albums } = $props();
</script>

<div class="container mx-auto p-6">
	<div class="mb-8 flex items-center justify-between">
		<h1 class="text-4xl font-bold text-foreground">Dashboard</h1>
	</div>
	<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
		<Card class="border-white/20 bg-white/10 backdrop-blur-sm">
			<CardContent class="p-6">
				<h3 class="mb-2 text-lg font-semibold text-foreground">Total Plays</h3>
				<p class="text-3xl font-bold text-purple-400">12,847</p>
				<p class="mt-2 text-sm text-gray-400">+15% from last month</p>
			</CardContent>
		</Card>
		<Card class="border-white/20 bg-white/10 backdrop-blur-sm">
			<CardContent class="p-6">
				<h3 class="mb-2 text-lg font-semibold text-foreground">Albums</h3>
				<p class="text-3xl font-bold text-blue-400">{albums.length}</p>
				<p class="mt-2 text-sm text-gray-400">Across all artists</p>
			</CardContent>
		</Card>
		<Card class="border-white/20 bg-white/10 backdrop-blur-sm">
			<CardContent class="p-6">
				<h3 class="mb-2 text-lg font-semibold text-foreground">Top Genre</h3>
				<p class="text-3xl font-bold text-green-400">Electronic</p>
				<p class="mt-2 text-sm text-gray-400">42% of total plays</p>
			</CardContent>
		</Card>
	</div>
</div>
