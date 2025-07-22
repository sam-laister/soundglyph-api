<script lang="ts">
	import AlbumsGrid from '$lib/components/app/AlbumsGrid.svelte';
	import { goto } from '$app/navigation';
	import Tabbar from '@/components/liquid/tabbar.svelte';
	import { setCurrentTrack } from '$lib/stores/player';

	// Mock data
	const albums = [
		{
			id: 1,
			title: 'Midnight Dreams',
			artist: 'Luna Eclipse',
			cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg',
			year: 2024,
			tracks: [
				{ id: 1, title: 'Starlight Serenade', duration: '3:42', track: 1 },
				{ id: 2, title: 'Neon Nights', duration: '4:15', track: 2 },
				{ id: 3, title: 'Digital Dreams', duration: '3:28', track: 3 },
				{ id: 4, title: 'Cosmic Dance', duration: '5:01', track: 4 },
				{ id: 5, title: 'Midnight Reverie', duration: '4:33', track: 5 }
			]
		},
		{
			id: 2,
			title: 'Electric Waves',
			artist: 'Synth Masters',
			cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg',
			year: 2024,
			tracks: [
				{ id: 6, title: 'Voltage', duration: '3:55', track: 1 },
				{ id: 7, title: 'Circuit Breaker', duration: '4:22', track: 2 },
				{ id: 8, title: 'Neon Highway', duration: '3:47', track: 3 },
				{ id: 9, title: 'Electric Storm', duration: '4:58', track: 4 }
			]
		},
		{
			id: 3,
			title: 'Ocean Depths',
			artist: 'Aqua Sounds',
			cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg',
			year: 2023,
			tracks: [
				{ id: 10, title: 'Deep Blue', duration: '4:12', track: 1 },
				{ id: 11, title: 'Tidal Wave', duration: '3:38', track: 2 },
				{ id: 12, title: 'Coral Gardens', duration: '5:15', track: 3 },
				{ id: 13, title: 'Submarine', duration: '4:44', track: 4 },
				{ id: 14, title: 'Surface Tension', duration: '3:59', track: 5 }
			]
		},
		{
			id: 4,
			title: 'Urban Jungle',
			artist: 'City Beats',
			cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg',
			year: 2024,
			tracks: [
				{ id: 15, title: 'Concrete Dreams', duration: '3:33', track: 1 },
				{ id: 16, title: 'Street Lights', duration: '4:07', track: 2 },
				{ id: 17, title: 'Rush Hour', duration: '3:51', track: 3 },
				{ id: 18, title: 'Rooftop Views', duration: '4:29', track: 4 }
			]
		},
		{
			id: 5,
			title: 'Retro Vibes',
			artist: 'Vintage Soul',
			cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg',
			year: 2023,
			tracks: [
				{ id: 19, title: 'Groovy Nights', duration: '4:18', track: 1 },
				{ id: 20, title: 'Disco Fever', duration: '3:42', track: 2 },
				{ id: 21, title: 'Funky Town', duration: '4:55', track: 3 },
				{ id: 22, title: 'Saturday Night', duration: '3:27', track: 4 }
			]
		},
		{
			id: 6,
			title: 'Cosmic Journey',
			artist: 'Space Travelers',
			cover: 'https://upload.wikimedia.org/wikipedia/en/a/af/Drake_-_Views_cover.jpg',
			year: 2024,
			tracks: [
				{ id: 23, title: 'Launch Sequence', duration: '2:15', track: 1 },
				{ id: 24, title: 'Zero Gravity', duration: '4:33', track: 2 },
				{ id: 25, title: 'Nebula Dance', duration: '5:22', track: 3 },
				{ id: 26, title: 'Black Hole', duration: '6:18', track: 4 },
				{ id: 27, title: 'Return to Earth', duration: '4:07', track: 5 }
			]
		}
	];

	function selectAlbum(album: any) {
		setCurrentTrack(album);
		goto(`/album/${album.id}`);
	}
</script>

<AlbumsGrid {albums} {selectAlbum} />
