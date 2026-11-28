import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/pawon.css',
                'resources/css/admin.css',
                'resources/js/pawon/pages/home.js',
                'resources/js/pawon/pages/about.js',
                'resources/js/pawon/pages/menu.js',
                'resources/js/pawon/pages/booking.js',
                'resources/js/pawon/pages/contact.js',
                'resources/js/pawon/pages/login.js',
                'resources/js/pawon/pages/register.js',
                'resources/js/pawon/pages/profile.js',
            ],
            refresh: true,
        }),
    ],
});