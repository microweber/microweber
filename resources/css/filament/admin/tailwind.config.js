import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
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
