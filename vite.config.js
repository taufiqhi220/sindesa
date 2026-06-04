import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({command}) => ({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    // Konfigurasi dev server — hanya aktif saat `npm run dev`
    // Sesuaikan host/HMR jika perlu akses dari perangkat lain di jaringan lokal
    ...(command === 'serve' ? {
        server: {
            host: '0.0.0.0',
            hmr: {
                host: 'localhost', // Ganti ke IP lokal jika akses dari HP/tablet
            },
        },
    } : {}),
}));
