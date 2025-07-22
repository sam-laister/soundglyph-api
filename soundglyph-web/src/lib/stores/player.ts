import { writable } from 'svelte/store';

export const currentTrack = writable(null);
export const currentAlbum = writable(null);
export const isPlaying = writable(false);
export const currentTime = writable(0);

export function togglePlay() {
    isPlaying.set(!isPlaying);
}
export function formatTime(seconds: number) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs.toString().padStart(2, '0')}`;
}

export function setCurrentTrack(track: any, album: any) {
    currentTrack.set(track);
    currentAlbum.set(album);
    isPlaying.set(true);
}
