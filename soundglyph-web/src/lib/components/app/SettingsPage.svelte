<script>
	import { Card, CardContent } from '$lib/components/ui/card';
	import { Separator } from '$lib/components/ui/separator';
	import router from '@lucide/svelte/icons/router';
	import Button from '../ui/button/button.svelte';
	import { goto } from '$app/navigation';

	function handleLogout() {
		goto('/auth/logout');
	}
</script>

<div class="container mx-auto p-6">
	<div class="mb-8 flex items-center justify-between">
		<h1 class="text-4xl font-bold text-foreground">Settings</h1>
	</div>
	<div class="max-w-2xl">
		<Card class="border-border bg-background backdrop-blur-sm">
			<CardContent class="space-y-6 p-6">
				<div>
					<h3 class="mb-4 text-lg font-semibold text-foreground">Audio Quality</h3>
					<div class="space-y-2">
						<label class="flex cursor-pointer items-center space-x-3">
							<input
								type="radio"
								name="quality"
								value="high"
								class="text-purple-600"
								checked
							/>
							<span class="text-foreground">High Quality (320kbps)</span>
						</label>
						<label class="flex cursor-pointer items-center space-x-3">
							<input
								type="radio"
								name="quality"
								value="medium"
								class="text-purple-600"
							/>
							<span class="text-foreground">Medium Quality (160kbps)</span>
						</label>
					</div>
				</div>
				<Separator class="bg-white/20" />
				<div>
					<h3 class="mb-4 text-lg font-semibold text-foreground">Playback</h3>
					<div class="space-y-3">
						<label class="flex cursor-pointer items-center justify-between">
							<span class="text-foreground">Crossfade</span>
							<input type="checkbox" class="toggle" />
						</label>
						<label class="flex cursor-pointer items-center justify-between">
							<span class="text-foreground">Gapless Playback</span>
							<input type="checkbox" class="toggle" checked />
						</label>
						<Button variant="destructive" size="sm" onclick={handleLogout}
							>Log out</Button
						>
					</div>
				</div>
			</CardContent>
		</Card>
	</div>
</div>
