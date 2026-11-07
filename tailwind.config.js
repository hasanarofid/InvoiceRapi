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
                invoicerapi: {
                    primary: '#6D28D9',       // Rich Purple (CTA, Active Nav, Pro badge)
                    secondary: '#8B5CF6',     // Violet (Accents, gradients, subtle glow)
                    tint: '#EDE9FE',          // Light violet background/pill badge
                    light: '#F5F3FF',         // Soft page background
                    success: '#22C55E',       // Green (Lunas, checklist)
                    warning: '#F59E0B',       // Amber (Sebagian, pending)
                    danger: '#EF4444',        // Red (Terlambat/Overdue)
                    dark: '#0F172A',          // Heading text
                    body: '#475569',          // Slate body text
                },
                synbarra: {
                    // Synbarra InvoiceRapi brand tokens
                    primary: 'var(--color-primary, #6D28D9)',
                    'primary-deep': 'var(--color-primary-deep, #4C1D95)',
                    secondary: 'var(--color-secondary, #8B5CF6)',
                    accent: 'var(--color-accent, #A78BFA)',
                    surface: 'var(--color-surface, #FFFFFF)',
                    'surface-dark': 'var(--color-surface-dark, #1E1B4B)',
                    border: 'var(--color-border, #E2E8F0)',
                    'border-dark': 'var(--color-border-dark, #312E81)',
                },
            },
            fontFamily: {
                sans: ['"Inter"', '"Plus Jakarta Sans"', 'Poppins', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            boxShadow: {
                'invoicerapi-sm': '0 2px 8px -2px rgba(109, 40, 217, 0.08)',
                'invoicerapi-md': '0 12px 30px -10px rgba(109, 40, 217, 0.12)',
                'invoicerapi-lg': '0 24px 50px -12px rgba(109, 40, 217, 0.18)',
                'invoicerapi-glow': '0 0 25px -4px rgba(139, 92, 246, 0.35)',
                'synbarra-sm': '0 2px 8px -2px rgba(109, 40, 217, 0.08)',
                'synbarra-md': '0 12px 30px -10px rgba(109, 40, 217, 0.12)',
                'synbarra-lg': '0 24px 50px -12px rgba(109, 40, 217, 0.18)',
                'synbarra-glow': '0 0 25px -4px rgba(139, 92, 246, 0.35)',
            },
        },
    },

    plugins: [forms],
};
