import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    safelist: [
        // Category / sidebar dynamic colours
        { pattern: /^bg-(green|blue|purple|orange|teal|indigo|pink|red|yellow)-(500|600|700)$/ },
        { pattern: /^text-(green|blue|purple|orange|teal|indigo|pink|red|yellow)-(500|600|700)$/ },
        { pattern: /^ring-(green|blue|purple|orange|teal|indigo|pink|red|yellow)-(400|500|600)$/ },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
