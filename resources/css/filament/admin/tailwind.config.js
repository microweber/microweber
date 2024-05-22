import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    plugins: [
        require("@tailwindcss/typography"),
        require("daisyui")
    ],
    daisyui: {
        themes: ["light", "dark"],
        base: false,
        styled: true,
        utils: true,
        prefix: "",
        logs: true,
        themeRoot: ":root",
    },
    mode: 'jit',
    content: [
        './app/Filament/Admin/**/*.php',
        './resources/views/filament/admin/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './src/MicroweberPackages/**/*.blade.php',
    ],
    // safelist: [
    //     {
    //         pattern: /./, // the "." means "everything"
    //     },
    // ],
}
