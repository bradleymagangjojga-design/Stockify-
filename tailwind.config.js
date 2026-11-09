import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './node_modules/flowbite/**/*.js',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Stockify brand palette
                brand: {
                    50:  '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                    950: '#172554',
                },
                // Sidebar dark theme
                sidebar: {
                    DEFAULT: '#0f172a',
                    hover:   '#1e293b',
                    active:  '#1e40af',
                    border:  '#1e293b',
                    text:    '#cbd5e1',
                    muted:   '#64748b',
                },
            },
            boxShadow: {
                'brand':     '0 10px 15px -3px rgba(59, 130, 246, 0.3)',
                'brand-lg':  '0 20px 25px -5px rgba(59, 130, 246, 0.35)',
            },
            animation: {
                'fade-in':  'fadeIn 0.3s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'pulse-dot': 'pulseDot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%':   { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                pulseDot: {
                    '0%, 100%': { opacity: '1' },
                    '50%':      { opacity: '.5' },
                },
            },
        },
    },
    plugins: [
        forms({
            strategy: 'class', // use `form-input` classes instead of auto-styling
        }),
        require('flowbite/plugin'),
    ],
};