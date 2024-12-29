const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/tw-elements/dist/js/**/*.js',
        "./node_modules/flowbite/**/*.js",

    ],

    theme: {
        extend: {
            backgroundImage: {
                'hero-pattern': "url('./assets/images/sIM90.svg')",

            },
            colors: {
                primary: "#1d2c37",
                secondary: "#f9ac44",
                title: "rgb(0, 131, 194)",
                main: "rgb(233, 51, 40)",
                main100: "rgba(233, 51, 40,0.5)"
            },
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],

            },
        },

    },

    plugins: [
        require('@tailwindcss/forms'),
        require('tw-elements/dist/plugin'),
        require('tailwindcss-rtl'),
        require('flowbite/plugin')

    ],
};
