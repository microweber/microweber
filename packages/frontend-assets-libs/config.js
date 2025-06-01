const localScripts = [

    //x: {target: `jseldom`, path: `./resources/local-libs/jseldom-jquery.js`, output: `../frontend-assets/resources/assets/libs`},
    //x: {target: `collapse-nav`, path: `./resources/local-libs/collapse-nav/collapse-nav.js`},
     //x:{target: `highlight-js`, path: `./resources/local-libs/highlight/highlight.min.js`},

     {target: `jquery-nested-sortable`, path: `./resources/local-libs/nested-sortable/jquery.mjs.nestedSortable.js`},
     {target: `slick`, path: `./resources/local-libs/mw-slick.js`},
     {target: `justified-gallery`, path: `./resources/local-libs/justified-gallery/justified-gallery.js`},

];



const nodeModulesScripts = [
    {target: `jquery`, path: `node_modules/jquery/dist/jquery.js`},
    {target: `jquery-ui`, path: `node_modules/jquery-ui/dist/jquery-ui.js`},

    {target: `nouislider`, path: `node_modules/nouislider/dist/nouislider.js`},
    {target: `tinymce`, path: `node_modules/tinymce/tinymce.js`},
    {target: `bxslider`, path: `node_modules/bxslider/dist/jquery.bxslider.min.js`},
    {target: `slick`, path: `node_modules/slick-carousel/slick/slick.js`},
    //{target: `swiper`, path: `node_modules/swiper/swiper.js`},
    {target: `api`, path: `node_modules/xss/dist/xss.js`},
    {target: `swiper`, path: `node_modules/swiper/swiper-bundle.min.js`},
    {target: `masonry`, path: `node_modules/masonry-layout/dist/masonry.pkgd.js`},
    {target: `codemirror`, path: `node_modules/codemirror/lib/codemirror.js`, process: false},
    {target: `easymde`, path: `node_modules/easymde/dist/easymde.min.js`, process: false},
    {target: `bootstrap_datepicker`, path: `node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js`, process: false},
    {target: `webfontloader`, path: `node_modules/webfontloader/webfontloader.js`, process: false},

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
    {target: `codemirror`, path: `node_modules/codemirror/lib/codemirror.css`},

    {target: `swiper`, path: `node_modules/swiper/swiper-bundle.min.css`},
    {target: `easymde`, path: `node_modules/easymde/dist/easymde.min.css` },
    {target: `async-alpine`, path: `node_modules/async-alpine/dist/async-alpine.script.js` },
    {target: `justified-gallery`, path: `./resources/local-libs/justified-gallery/justified-gallery.css`},
    {target: `bootstrap_datepicker`, path: `node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css`},

];

const assets = [
    {target: `jquery-ui`, path: `node_modules/jquery-ui/dist/themes/base/images`},
    {target: `material-icons`, path: `node_modules/material-icons/iconfont`},
    {target: `font-awesome`, path: `./resources/local-libs/font-awesome-4.7/fonts`},
    {target: `mw-icons-mind`, path: `./resources/local-libs/mw-icons-mind`},
    {target: `slick`, path: `node_modules/slick-carousel/slick/ajax-loader.gif`},
    {target: `bxslider`, path: `node_modules/bxslider/dist/images`},

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

const modifyIcons = async (data) => {
    const fs = require('fs');
    const targetFiles = [
        `${data.target}/flag-icons.min.css`,
        `${data.target}/flag-icons.css`,
    ];
    await Promise.all(targetFiles.map(targetFile => {
        return new Promise(resolve => {
            fs.readFile(targetFile, 'utf8', function (err,data) {
                if (err) {
                    resolve()
                    return console.log(err);
                }
                var result = data.replace(/.fi/g, '.mw-flag-icon');
                setTimeout((result, targetFile) => {
                    fs.writeFile(targetFile, result, 'utf8', function (err) {
                        resolve()
                        if (err) return console.log(err);
                    });
                  }, 2120, result, targetFile);
            });
        })
    }))

}

const copy = [
    {target: `flag-icons/css`, path: `node_modules/flag-icons/css`, afterCopy: modifyIcons},
    {target: `flag-icons/flags`, path: `node_modules/flag-icons/flags`},
    {target: `flag-icons/country.json`, path: `node_modules/flag-icons/country.json`},
    {target: `api/`, path: `resources/local-libs/api`},
    {target: `css/`, path: `resources/local-libs/css`},
    {target: `img/`, path: `resources/local-libs/img`},
    {target: `slick/fonts`, path: `node_modules/slick-carousel/slick/fonts`},
];


const output = `./resources/dist`;


module.exports = {
    scripts, css, output, assets, copy
}
