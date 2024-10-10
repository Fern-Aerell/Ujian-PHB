import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

declare global {
    interface Window {
        Pusher: typeof Pusher;
        Echo: Echo;
    }
}

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY as string,
    wsHost: import.meta.env.VITE_REVERB_HOST as string,
    wsPort: (import.meta.env.VITE_REVERB_PORT as string | undefined) ?? 80,
    wssPort: (import.meta.env.VITE_REVERB_PORT as string | undefined) ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME as string | undefined ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});