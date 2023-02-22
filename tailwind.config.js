const colors = require("tailwindcss/colors");

const mwTheme = require("./resources/css/mwTheme.js");



module.exports = {
    content: [
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    darkMode: "class",
    theme: mwTheme.theme,
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
