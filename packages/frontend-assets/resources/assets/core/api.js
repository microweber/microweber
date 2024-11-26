import MicroweberBaseClass from "../containers/base-class.js";


;(function ( ) {
    async function xhr(options= {}){
        const {url, data, method, headers} = options;
        let headersDefaults =  {
            'Content-type': 'application/json; charset=UTF-8'
        };

        let request = {
            method: method || 'GET',
            headers: Object.assign({}, headersDefaults, headers || {})
        };

        if(!!data) {
            request.body = JSON.stringify(data);
        }

        let page = await fetch(url,request);
        let json = await page.json();
        return json;
    }

    class MWAPI extends MicroweberBaseClass {
        constructor(options = {}) {
            super();
            const defaults = {
                baseURL: mw.settings.api_url
            };
            this.settings = Object.assign({}, defaults, options)
        }
        async #xhr(options) {
            if(options.url.indexOf('http') === -1) {
                options.url = `${this.settings.baseURL}${options.url}`
            }
            return await xhr(options);
        }



        category = {

            create: async (id) => {

            },
            edit: async (id) => {

            },
            delete: async (id) => {
                const res = await this.#xhr({
                    url:'category/delete/' + id,
                    method: 'DELETE',
                });
                mw.notification.success(mw.lang('Category deleted'));
                return res;
            }
        };

        post = {
            async create(data) {

            },
            async edit(data) {

            },
            async delete(data) {

            }
        };

        product = {
            async create(data) {

            },
            async edit(data) {

            },
            async delete(data) {

            }
        };
    }


    mw.api = new MWAPI();
})();
