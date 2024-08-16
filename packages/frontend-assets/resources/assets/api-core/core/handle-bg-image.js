import {HandleMenu} from "./handle-menu";
import {HandleIcons} from "./handle-icons";

export class BGImageHandles {
    constructor(options = {}) {
        const defaults = {
            document: document
        }
        this.settings = Object.assign({}, defaults, options);
        this.init()

    }

    #menu(){
        const handleIcons = new HandleIcons();


        const primaryMenu = [
            {
                title: 'Edit' ,
                text: '',
                icon: handleIcons.icon('edit'),

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

                            mw.top().app.cssEditor.temp(target, 'background-image', `url(${url})`);
                            mw.app.liveEdit.play();
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

                        mw.app.liveEdit.play();
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


