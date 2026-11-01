import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                synbarra: {
                    // Approved brand tokens directly extracted from logo
                    primary: 'var(--color-primary, #01232B)',
                    'primary-deep': 'var(--color-primary-deep, #011E26)',
                    secondary: 'var(--color-secondary, #00F1DE)',
                    accent: 'var(--color-accent, #04D2C5)',
                    surface: 'var(--color-surface, #F8FAFA)',
                    'surface-dark': 'var(--color-surface-dark, #022B31)',
                    border: 'var(--color-border, #E2E8F0)',
                    'border-dark': 'var(--color-border-dark, #0A3B43)',
                },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            boxShadow: {
                'synbarra-sm': '0 2px 8px -2px rgba(1, 35, 43, 0.08)',
                'synbarra-md': '0 12px 30px -10px rgba(1, 35, 43, 0.12)',
                'synbarra-lg': '0 24px 50px -12px rgba(1, 35, 43, 0.18)',
                'synbarra-glow': '0 0 25px -4px rgba(0, 241, 222, 0.35)',
                'synbarra-glow-subtle': '0 0 15px -2px rgba(4, 210, 197, 0.25)',
            },
        },
    },

    plugins: [forms],
};
