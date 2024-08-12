const preset = require('./vendor/filament/filament/tailwind.config.preset')

module.exports = {
    presets: [preset],

    theme: {
        extend: {
            colors: {
                blue: {
                    50: '#edf5ff',
                    100: '#d0e2ff',
                    200: '#a6c8ff',
                    300: '#78a9ff',
                    400: '#4589ff',
                    500: '#4991fc',
                    600: '#4592ff',
                    700: '#0f62fe',
                    800: '#001d6f',
                    900: '#001141',
                },
                gray: {
                    '50': '#f9fafb',
                    '100': '#f3f4f6',
                    '200': '#e5e7eb',
                    '300': '#d1d5db',
                    '400': '#9ca3af',
                    '500': '#6b7280',
                    '600': '#182433',
                    '700': '#182433',
                    '800': '#182433',
                    '900': '#182433',
                    '950': '#030712'
                }
            },
        },
    },
    plugins: [

        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/container-queries'),

    ],

    content: [
        './../../app/Filament/Admin/**/*.php',
        './../../resources/views/filament/admin/**/*.blade.php',
        './../../resources/**/*.blade.php',
        './resources/**/*.css',
        './resources/**/*.scss',
        './../../vendor/filament/**/*.blade.php',
        './../../src/MicroweberPackages/**/*.blade.php',
        './../../src/MicroweberPackages/**/resources/views/**/*.blade.php',
        './../../vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
        './../../userfiles/modules/**/*.blade.php',
        './../../userfiles/modules/**/src/resources/views/**/*.blade.php',
        './../../userfiles/modules/*/src/resources/views/**/*.blade.php',
    ]
}
