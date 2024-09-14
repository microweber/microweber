const localScripts = [

    {target: `jseldom`, path: `./resources/local-libs/jseldom-jquery.js`, output: `../frontend-assets/resources/assets/libs`},
    {target: `collapse-nav`, path: `./resources/local-libs/collapse-nav/collapse-nav.js`},

];



const nodeModulesScripts = [
    {target: `jquery`, path: `node_modules/jquery/dist/jquery.js`},
    {target: `jquery-ui`, path: `node_modules/jquery-ui/dist/jquery-ui.js`},
    {target: `jquery-nested-sortable`, path: `node_modules/nestedSortable/jquery.mjs.nestedSortable.js`},
    {target: `nouislider`, path: `node_modules/nouislider/dist/nouislider.js`},
    {target: `tinymce`, path: `node_modules/tinymce/tinymce.js`},
    {target: `bxslider`, path: `node_modules/bxslider/dist/jquery.bxslider.min.js`},
    {target: `slick`, path: `node_modules/slick-carousel/slick/slick.js`},
    {target: `swiper`, path: `node_modules/swiper/swiper.js`},

    {
        target: `rangy`,
        path: [
            `node_modules/rangy/lib/rangy-core.js`,
            `node_modules/rangy/lib/rangy-classapplier.js`,
            `node_modules/rangy/lib/rangy-selectionsaverestore.js`,
            `node_modules/rangy/lib/rangy-serializer.js`,
        ]
    },
];


const scripts = [
    ...nodeModulesScripts,
    ...localScripts,
];






const css = [
    {target: `jquery-ui`, path: `node_modules/jquery-ui/dist/themes/base/jquery-ui.css`},
    {target: `nouislider`, path: `node_modules/nouislider/dist/nouislider.css`},
    {target: `material-icons`, path: `node_modules/material-icons/iconfont/material-icons.css`},
    {target: `bxslider`, path: `node_modules/bxslider/dist/jquery.bxslider.css`},
    {target: `slick`, path: `node_modules/slick-carousel/slick/slick.css`},
    {target: `collapse-nav`, path: `./resources/local-libs/collapse-nav/collapse-nav.css`},
    {target: `font-awesome`, path: `./resources/local-libs/font-awesome-4.7/css/font-awesome.css`},


    {target: `swiper`, path: `node_modules/swiper/swiper.css`},

];

const assets = [
    {target: `jquery-ui`, path: `node_modules/jquery-ui/dist/themes/base/images`},
    {target: `material-icons`, path: `node_modules/material-icons/iconfont`},
    {target: `font-awesome`, path: `./resources/local-libs/font-awesome-4.7/fonts`},
    {
        target: `tinymce`,
        path: [
            `node_modules/tinymce/icons`,
            `node_modules/tinymce/plugins`,
            `node_modules/tinymce/skins`,
            `node_modules/tinymce/themes`,
        ]
    },
];

const copy = [
    {target: `flag-icons/css`, path: `node_modules/flag-icons/css`},
    {target: `flag-icons/flags`, path: `node_modules/flag-icons/flags`},
    {target: `flag-icons/country.json`, path: `node_modules/flag-icons/country.json`},
];


const output = `./resources/dist`;


export const config = {
    scripts, css, output, assets, copy
}
