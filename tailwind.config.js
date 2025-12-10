import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            // Konfigurasi Warna Anda
            colors: {
                'brand-black': '#222222',
                'brand-red': '#A4161A',
                'brand-gold': '#D4AF37',
            },
            // Konfigurasi Font Anda
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            // --- TAMBAHAN UNTUK RUNNING TEXT ---
            animation: {
                marquee: 'marquee 25s linear infinite',
            },
            keyframes: {
                marquee: {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(-100%)' },
                }
            },
            // -----------------------------------
        },
    },

    plugins: [
        forms,
        typography,
    ],
};