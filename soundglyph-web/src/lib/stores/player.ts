import type { AlbumBasicResponse } from '@/api/admin/resources/albums-api';
import { getMediaUrl } from '@/api/admin/resources/media-api';
import type { TrackBasicResponse } from '@/api/admin/resources/tracks-api';
import { writable } from 'svelte/store';

export function formatTime(seconds: number) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs.toString().padStart(2, '0')}`;
}

let audioInstance: HTMLAudioElement | null = null;

export const currentTrack = writable<TrackBasicResponse | null>(null);
export const currentAlbum = writable<AlbumBasicResponse | null>(null);
export const isPlaying = writable(false);
export const isLoading = writable(false);
export const currentTime = writable(0);

export function togglePlay() {
    if (!audioInstance) return;

    if (audioInstance.paused) {
        audioInstance.play();
        isPlaying.set(true);
    } else {
        audioInstance.pause();
        isPlaying.set(false);
    }
}

export async function setCurrentTrack(track: TrackBasicResponse, album: AlbumBasicResponse) {
    isLoading.set(true);

    try {
        // Stop any currently playing audio
        if (audioInstance) {
            audioInstance.pause();
            audioInstance.src = '';
            audioInstance = null;
        }

        const audioUrl = await getMediaUrl(track.audio.id);
        audioInstance = new Audio(audioUrl);

        // Set up event listeners
        audioInstance.addEventListener('ended', () => {
            isPlaying.set(false);
        });

        audioInstance.addEventListener('timeupdate', () => {
            currentTime.set(audioInstance?.currentTime || 0);
        });

        // Update stores
        currentTrack.set(track);
        currentAlbum.set(album);

        // Start playing
        await audioInstance.play();
        isPlaying.set(true);

    } catch (error) {
        console.error('Error playing track:', error);
        isPlaying.set(false);
    } finally {
        isLoading.set(false);
    }
}

export function stop() {
    if (audioInstance) {
        audioInstance.pause();
        audioInstance.currentTime = 0;
        isPlaying.set(false);
    }
}

export function seek(time: number) {
    if (audioInstance) {
        audioInstance.currentTime = time;
    }
}
