<script lang="ts">
	import { Button } from '$lib/components/ui/button';
	import { Input } from '$lib/components/ui/input';
	import { goto } from '$app/navigation';
	import { page } from '$app/state';

	let title = $state('');
	let artist = $state('');
	let artwork: File | null = $state(null);
	let loading = $state(false);

	async function handleSubmit() {
		if (!title.trim() || !artist.trim()) return;

		loading = true;
		try {
			const formData = new FormData();
			formData.append('title', title);
			formData.append('artist', artist);
			if (artwork) {
				formData.append('artwork', artwork);
			}

			const response = await fetch('/api/albums', {
				method: 'POST',
				body: formData
			});

			if (response.ok) {
				const newAlbum = await response.json();
				// Close modal and redirect to new album
				goto(`/album/${newAlbum.id}`);
			} else {
				console.error('Failed to create album');
			}
		} catch (error) {
			console.error('Error creating album:', error);
		} finally {
			loading = false;
		}
	}

	function handleClose() {
		goto(page.url.pathname);
	}

	function handleFileChange(event: Event) {
		const target = event.target as HTMLInputElement;
		if (target.files && target.files[0]) {
			artwork = target.files[0];
		}
	}
</script>

<div class="space-y-6">
	<div class="text-center">
		<h2 class="text-foreground text-2xl font-bold">Create New Album</h2>
		<p class="text-muted-foreground mt-2">Add a new album to your library</p>
	</div>

	<form
		onsubmit={(e) => {
			e.preventDefault();
			handleSubmit();
		}}
		class="space-y-4"
	>
		<div>
			<label for="title" class="text-foreground mb-2 block text-sm font-medium">
				Album Title
			</label>
			<Input
				id="title"
				type="text"
				bind:value={title}
				placeholder="Enter album title"
				required
			/>
		</div>

		<div>
			<label for="artist" class="text-foreground mb-2 block text-sm font-medium">
				Artist
			</label>
			<Input
				id="artist"
				type="text"
				bind:value={artist}
				placeholder="Enter artist name"
				required
			/>
		</div>

		<div>
			<label for="artwork" class="text-foreground mb-2 block text-sm font-medium">
				Album Artwork (Optional)
			</label>
			<Input id="artwork" type="file" accept="image/*" onchange={handleFileChange} />
		</div>

		<div class="flex gap-3 pt-4">
			<Button type="button" variant="outline" onclick={handleClose} class="flex-1">
				Cancel
			</Button>
			<Button
				type="submit"
				disabled={loading || !title.trim() || !artist.trim()}
				class="flex-1"
			>
				{loading ? 'Creating...' : 'Create Album'}
			</Button>
		</div>
	</form>
</div>
