const colors = require("tailwindcss/colors");

module.exports = {
    important: '.filament-layouts-app',
    content: [
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './src/**/*.blade.php',
        './src/**/resources/views/*.blade.php',
        './userfiles/**/*.blade.php',
        './vendor/filament/packages/admin/resources/**/*.blade.php',
        './vendor/filament/packages/forms/resources/**/*.blade.php',
        './vendor/filament/packages/notifications/resources/**/*.blade.php',
        './vendor/filament/packages/support/resources/**/*.blade.php',
        './vendor/filament/packages/tables/resources/**/*.blade.php',
    ],
    // corePlugins: {
    //      margin: false,
    //      padding: false,
    // },
    darkMode: "class",
    // safelist: [
    //     {
    //         pattern: /./, // the "." means "everything"
    //     },
    // ],
    presets: [
        require('./resources/css/mwTheme.js')
    ],
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("@tailwindcss/aspect-ratio"),
        require("@tailwindcss/line-clamp"),
    ],
};
