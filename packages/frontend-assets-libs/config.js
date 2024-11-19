const localScripts = [

    {target: `jseldom`, path: `./resources/local-libs/jseldom-jquery.js`, output: `../frontend-assets/resources/assets/libs`},
    {target: `collapse-nav`, path: `./resources/local-libs/collapse-nav/collapse-nav.js`},
    {target: `highlight-js`, path: `./resources/local-libs/highlight/highlight.min.js`},
    {target: `jquery-nested-sortable`, path: `./resources/local-libs/nested-sortable/jquery.mjs.nestedSortable.js`},

];



const nodeModulesScripts = [
    {target: `jquery`, path: `node_modules/jquery/dist/jquery.js`},
    {target: `jquery-ui`, path: `node_modules/jquery-ui/dist/jquery-ui.js`},

    {target: `nouislider`, path: `node_modules/nouislider/dist/nouislider.js`},
    {target: `tinymce`, path: `node_modules/tinymce/tinymce.js`},
    {target: `bxslider`, path: `node_modules/bxslider/dist/jquery.bxslider.min.js`},
    {target: `slick`, path: `node_modules/slick-carousel/slick/slick.js`},
    //{target: `swiper`, path: `node_modules/swiper/swiper.js`},
    {target: `xss`, path: `node_modules/xss/dist/xss.js`},
    {target: `swiper`, path: `node_modules/swiper/swiper-bundle.min.js`},
    {target: `masonry`, path: `node_modules/masonry-layout/dist/masonry.pkgd.js`},


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
    {target: `slick`, path: `node_modules/slick-carousel/slick/slick-theme.css`},
    {target: `collapse-nav`, path: `./resources/local-libs/collapse-nav/collapse-nav.css`},
    {target: `font-awesome`, path: `./resources/local-libs/font-awesome-4.7/css/font-awesome.css`},
    {target: `highlight-js`, path: `./resources/local-libs/highlight/styles/default.css`},



    {target: `swiper`, path: `node_modules/swiper/swiper-bundle.min.css`},


];

const assets = [
    {target: `jquery-ui`, path: `node_modules/jquery-ui/dist/themes/base/images`},
    {target: `material-icons`, path: `node_modules/material-icons/iconfont`},
    {target: `font-awesome`, path: `./resources/local-libs/font-awesome-4.7/fonts`},
    {target: `mw-icons-mind`, path: `./resources/local-libs/mw-icons-mind`},

    {
        target: `tinymce`,
        path: [
            `node_modules/tinymce/icons`,
            `node_modules/tinymce/plugins`,
            `node_modules/tinymce/skins`,
            `node_modules/tinymce/themes`,
        ]
    },
    {
        target: `mdi`,
        path: [
            `node_modules/@mdi/font/css`,
            `node_modules/@mdi/font/fonts`,

        ]
    },

];

const copy = [
    {target: `flag-icons/css`, path: `node_modules/flag-icons/css`},
    {target: `flag-icons/flags`, path: `node_modules/flag-icons/flags`},
    {target: `flag-icons/country.json`, path: `node_modules/flag-icons/country.json`},
    {target: `api/`, path: `resources/local-libs/api`},
    {target: `css/`, path: `resources/local-libs/css`}
];


const output = `./resources/dist`;


export const config = {
    scripts, css, output, assets, copy
}
