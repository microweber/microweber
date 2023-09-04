import {HandleMenu} from "./handle-menu";

export class BGImageHandles {
    constructor(options = {}) {
        const defaults = {
            document: document
        }
        this.settings = Object.assign({}, defaults, options);
        this.init()

    }

    #menu(){

        const primaryMenu = [
            {
                title: 'Edit' ,
                text: '',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M181.674-179.761h41.13l441.087-441.565-41.13-41.13-441.087 441.565v41.13Zm613.043-484.326L665.761-793.043l36.978-37.218q19.631-19.63 47.859-19.75 28.228-.119 47.859 19.272l37.782 37.782q18.435 18.196 17.837 44.153-.598 25.956-18.315 43.674l-41.044 41.043Zm-41.76 41.761L247.761-117.13H118.804v-128.957l504.957-504.956 129.196 128.717Zm-109.392-19.565-20.804-20.565 41.13 41.13-20.326-20.565Z"></path></svg>',

                action: function(target) {
                    var dialog;
                    var picker = new mw.filePicker({
                        type: 'images',
                        label: false,
                        autoSelect: false,
                        footer: true,
                        _frameMaxHeight: true,
                        onResult: function(res) {
                            var url = res.src ? res.src : res;
                            if(!url) {
                                dialog.remove();
                                return
                            }
                            url = url.toString();
                            target.style.backgroundImage = `url(${url})`; ;
                            mw.app.get('liveEdit').play();
                            dialog.remove();
                            mw.top().app.registerChange(target);
                        }
                    });
                    dialog = mw.top().dialog({
                        content: picker.root,
                        title: mw.lang('Select image'),
                        footer: false,
                        width: 860,
                    });
                    picker.$cancel.on('click', function(){
                        dialog.remove()
                    })


                    $(dialog).on('Remove', () => {

                        mw.app.get('liveEdit').play();
                    })
                }

            },
        ];

        this.menu = new HandleMenu({
            id: 'mw-bg-image-handles-menu',
            title: '',


            menus: [
                {
                    name: 'primary',
                    nodes: primaryMenu
                },

            ],

        });

        this.menu.show()
    }

    hide() {
        this.handle.hide()
    }

    show() {
        this.handle.show()
    }

    position(target) {
        const win = target.ownerDocument.defaultView;
        var rect = target.getBoundingClientRect();
        rect.offsetTop = rect.top + win.pageYOffset;
        rect.offsetBottom = rect.bottom + win.pageYOffset;
        rect.offsetLeft = rect.left + win.pageXOffset;



        this.handle.css({
            top: rect.offsetTop,
            left: rect.offsetLeft,
            width: rect.width,
            height: rect.height
        })
    }



    #target;

    getTarget() {
        return this.#target;
    }

    setTarget(target) {
        this.#target = target;
        if(!target) {
            this.hide()
        } else {
            this.position(this.#target);
            this.menu.setTarget(this.#target)
            this.menu.show()
            this.show()
        }
    }


    build() {
        const handle = mw.element(`
                    <div class="mw-bg-image-handles">

                    </div>
                `)
        this.#menu();
        handle.append(this.menu.root);

        this.settings.document.body.append(handle.get(0))

        this.handle = handle;


    }

    init() {
        this.build()
    }
}


