const entry = {

   //'frontend': './resources/assets/js/frontend.js',
   //'live-edit-page-scripts': './resources/assets/live-edit/live-edit-page-scripts.js',
  // core: './resources/assets/js/core.js',
  // admin: './resources/assets/js/admin.js',
  // admincss: './resources/assets/css/admin.scss',
  // install: './resources/assets/css/install.scss',
   imageeditor: './node_modules/filerobot-image-editor/lib/index.js',
};

const outputJS = `./resources/dist/js`;
const outputCSS = `../css`; // relative to outputJS


export const config = {
    entry, outputJS, outputCSS
}
