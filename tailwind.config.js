import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        "./resources/**/*.js",
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./src/**/*.{html,js}",
        "./node_modules/tw-elements/dist/js/**/*.js"
    ],

        theme: {
            extend: {
                fontFamily: {
                    sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                },
                colors: {
                    emerald : colors.emerald,
                    red     : colors.red,
                    cus     : 'rgb(73, 100, 153)',
                    cus1    : 'rgba(32, 43, 63, 0.9)',
                    cus2    : 'rgb(206, 206, 206)'
                },
                padding:{

                },

                // spacing: {
                //     '1': '8px',
                //     '2': '12px',
                //     '3': '16px',
                //     '4': '24px',
                //     '5': '32px',
                //     '6': '48px',
                //   }

            },
        },

    plugins: [forms, typography,require("tw-elements/dist/plugin.cjs")],
    darkMode: "class"
};
