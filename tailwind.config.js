import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // ==========================================================
    // TAMBAHKAN BAGIAN SAFELIST DI SINI
    // ==========================================================
    safelist: [
        'bg-gray-200', 'text-gray-800',
        'bg-green-100', 'text-green-800',
        'bg-pink-100', 'text-pink-800',
        'bg-blue-100', 'text-blue-800',
        'bg-purple-100', 'text-purple-800',
        'bg-indigo-100', 'text-indigo-800',
        'bg-yellow-100', 'text-yellow-800',
        'bg-orange-100', 'text-orange-800',
        'bg-teal-100', 'text-teal-800',
        'bg-cyan-100', 'text-cyan-800',
    ],

    theme: {
        extend: {
            colors: {
                'dark-blue': '#14213D',
                'primary-yellow': '#FCA311',
                'light-gray': '#E5E5E5',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};