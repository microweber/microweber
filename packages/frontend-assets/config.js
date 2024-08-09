const entry = {
    core: './resources/assets/js/core.js',
    admin: './resources/assets/js/admin.js',
   admincss: './resources/assets/css/admin.scss',
};

const outputJS = `./resources/dist/js`;
const outputCSS = `../css`; // relative to outputJS


export const config = {
    entry, outputJS, outputCSS
}
