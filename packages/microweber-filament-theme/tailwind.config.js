const preset = require('./../../vendor/filament/filament/tailwind.config.preset')

module.exports = {
    future: {
        relativeContentPathsByDefault: true,
    },
    presets: [preset],
   // mode: 'jit',
   //  purge: [
   //      // Your CSS will rebuild any time *any* file in `src` changes
   //      './resources/assets/**/*.*',
   //  ],
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
   // safelist: [{ pattern: /./ }],


    content: {
        relative: true,
        files: [
            './../../app/Filament/Admin/**/*.php',
            './../../resources/**/*.blade.php',
            './resources/assets/css/*.css',
            './resources/assets/css/**/*.scss',
            './../../Modules/**/*.blade.php',
            './../../Modules/**/resources/views/**/*.blade.php',
            './../../src/MicroweberPackages/**/*.blade.php',
            './../../src/MicroweberPackages/**/resources/views/**/*.blade.php',
            './../../vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
            './../../vendor/filament/**/*.blade.php',
            './../../userfiles/modules/**/*.blade.php',
            './../../userfiles/modules/**/src/resources/views/**/*.blade.php',
            './../../userfiles/modules/*/src/resources/views/**/*.blade.php',
            './../../public/userfiles/modules/**/*.blade.php',
            './../../public/userfiles/modules/**/src/resources/views/**/*.blade.php',
            './../../public/userfiles/modules/*/src/resources/views/**/*.blade.php',
        ],
    },

    content111: [
        './../../app/Filament/Admin/**/*.php',
        './../../resources/**/*.blade.php',
        './resources/**/*.css',
        './resources/**/*.scss',
        './../../Modules/**/*.blade.php',
        './../../src/MicroweberPackages/**/*.blade.php',
        './../../src/MicroweberPackages/**/resources/views/**/*.blade.php',
        './../../vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
        './../../userfiles/modules/**/*.blade.php',
        './../../userfiles/modules/**/src/resources/views/**/*.blade.php',
        './../../userfiles/modules/*/src/resources/views/**/*.blade.php',
    ]
}
