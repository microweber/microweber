export class ModulesList {
    constructor( options = {} ) {
        const defaults = {
            mode: 'local',
            document: document,
            encapsulate: false,
            css: false,
            searchMode: 'local',
            data: [],
            skin: 'defaultModules' // 'defaultModules' | 'defaultLayouts'
        };

        this.settings = Object.assign({}, defaults, options);
        this.document = this.settings.document;
    }
    #data = null;
    #_e = {};
    selectedCategory = '';
    #modulesNodes = [];
    root = null;

    on(e, f){ this.#_e[e] ? this.#_e[e].push(f) : (this.#_e[e] = [f]) };
    dispatch(e, f){ this.#_e[e] ? this.#_e[e].forEach(c => { c.call(this, f); }) : ''; };

    getData() {
        return this.#data;
    }

    setData(data, trigger = true) {
        this.#data = data;
        let i = 0, length = this.#data.length;
        for( ; i < length; i++) {
            if (typeof this.#data[i].categories === 'string') {
                this.#data[i].categories = this.#data[i].categories.split(',').map(cat => cat.trim());
            }
        }
        if(trigger) {
            this.dispatch('dataChanged', this.#data)
        }
    }

    prepareCategories() {
        const categories = [];
        const data = this.getData();
        let i = 0, length = data.length;
        for( ; i < length; i++) {
            let item = data[i];
            let cats = item.categories || '';

            let ic = 0, lengthc = cats.length;
            for( ; ic < lengthc; ic++) {
                const cat = cats[ic].trim();
                if(categories.indexOf(cat) === -1) {
                    categories.push(cat)
                }
            }
        }
        categories.sort();
        this.categories = categories;
    }

    createCategoriesMenu() {
        const nav = this.document.createElement('ul');
        this.prepareCategories();
        nav.className = 'modules-list-categories';
        nav.innerHTML = this.categories.map(c => `<li data-category="${c}">${c}</li>`).join('');
        nav.innerHTML =  `<li data-category="">All categories</li> ${nav.innerHTML}`;
        nav.addEventListener('click', e => {
            if (e.target.nodeName === 'LI') {
                this.selectCategory(e.target.dataset.category);
            }
        })
        this.categoriesNavigation = nav
        this.rootShadow.appendChild(nav);
        this.on('categorySelect', category => {
            const items = nav.querySelectorAll('[data-category]');
            for( const node of items ) {
                node.classList[node.dataset.category === category ? 'add' : 'remove']('active');
            }
        })
    }


    selectCategory(category = '') {
        this.selectedCategory = category;
        this.search(category)
        this.dispatch('categorySelect', category);
    }
    createRoot() {
        this.root = this.document.createElement('div');
        this.root.className = 'modules-list modules-list-' + this.settings.skin;
        this.rootShadow = this.settings.encapsulate ? this.root.attachShadow({mode: 'open'}) : this.root;

        if(this.settings.css) {

            let style = document.createElement("style");

            style.textContent = this.settings.css;
            this.rootShadow.appendChild(style);

        }
    }
    #renderModule(data) {
        //todo: remove
        data.locked = data.description.includes('a');
        const moduleItem = this.document.createElement('div');
        moduleItem.className = 'modules-list-block-item modules-list-block-item-is-locked-' + data.locked;
        moduleItem.__$data = data;
        moduleItem.innerHTML = `
            <div class="modules-list-block-item-picture" style="background-image: url(${data.icon || data.screenshot || data.image})"></div>
            <div class="modules-list-block-item-title">${data.name || data.title}</div>
            <div class="modules-list-block-item-description">${data.description}</div>
            ${data.locked ? '<span class="modules-list-block-item-locked-badge">PRO</span>' : ''}
        `;
        moduleItem.addEventListener('click', e => {
            e.stopPropagation();
            if(moduleItem.__$data.locked) {
                this.dispatch('lockedModuleSelected', {data, element: moduleItem});
            } else {
                this.dispatch('moduleSelected', {data, element: moduleItem});
            }
        });
        moduleItem.$data = data;
        return moduleItem;
    }



    #categorizedBlock(categoryName) {
        const cat = this.document.createElement('div');

        cat.className = 'modules-list-block-category-section';
        cat.innerHTML = '<div class="modules-list-block-category-section-title"><h5>' + categoryName + '</h5></div>';
        cat.__$hasNodes = false;

        return cat;
    }

    renderModulesCategorized() {
        this.modulesList = this.document.createElement('div');
        this.modulesList.className = 'modules-list-block'

        this.modulesListNoResultsNode = this.document.createElement('div');
        this.modulesListNoResultsNode.className = 'modules-list-block-no-results'
        this.modulesListNoResultsNode.innerHTML = 'Nothing found...';
        this.modulesListNoResultsNode.style.display = 'none';

        const data = this.getData();
        let i = 0, length = data.length;
        const byCategory = {
            miscellaneous:  this.#categorizedBlock('miscellaneous')
        };
        for( ; i < length; i++) {
            const categories = data[i].categories || ['miscellaneous'];
            categories.forEach(cat => {
                if(!byCategory[cat]) {
                    byCategory[cat] = this.#categorizedBlock(cat);
                }
                const moduleItem = this.#renderModule(data[i]);
                this.#modulesNodes.push(moduleItem);
                byCategory[cat].appendChild(moduleItem);
                byCategory[cat].__$hasNodes = true;
            })
        }

        for (const bc in byCategory){
            if(!!byCategory[bc].__$hasNodes) {
                this.modulesList.appendChild(byCategory[bc])
            }

        }



        this.rootShadow.appendChild(this.modulesList)
        this.modulesList.appendChild(this.modulesListNoResultsNode)
    }

    renderModules() {
        this.modulesList = this.document.createElement('div');
        this.modulesList.className = 'modules-list-block'

        this.modulesListNoResultsNode = this.document.createElement('div');
        this.modulesListNoResultsNode.className = 'modules-list-block-no-results'
        this.modulesListNoResultsNode.innerHTML = 'Nothing found...';
        this.modulesListNoResultsNode.style.display = 'none';

        const data = this.getData();
        let i = 0, length = data.length;
        for( ; i < length; i++) {
            const moduleItem = this.#renderModule(data[i]);
            this.#modulesNodes.push(moduleItem);
            this.modulesList.appendChild(moduleItem)
        }
        this.rootShadow.appendChild(this.modulesList)
        this.modulesList.appendChild(this.modulesListNoResultsNode)
    }

    hideItem(item) {
        if (item.nodeName) {
            item.style.display = 'none';
        }
    }
    showItem(item) {
        if (item.nodeName) {
            item.style.display = '';
        }
    }

    createSearchGUI() {
        this.searchBlock = this.document.createElement('div');
        this.searchField = this.document.createElement('input');
        this.searchField.type = 'text';
        this.searchField.placeholder = 'Type to Search...';
        this.searchBlock.className = 'modules-list-search-block';
        this.searchField.className = 'modules-list-search-field';
        this.searchBlock.appendChild(this.searchField);
        this.rootShadow.appendChild(this.searchBlock);
        this.searchField.addEventListener('input', e => this.search(undefined, this.searchField.value))
        this.on('categorySelect', cat => {
            this.searchField.value = '';
        })
    }

    async #searchLocal(category, keyword) {
        if(category) {
            category = category.trim().toLowerCase();
        }
        if(keyword) {
            keyword = keyword.trim().toLowerCase();
        }
        return new Promise(resolve => {
            let i = 0, length = this.#modulesNodes.length;
            let found = 0;
            for ( ; i < length; i++) {
                const item = this.#modulesNodes[i];
                const data = item.$data;
                let matches = true;
                if(!!category && data.categories.indexOf(category) === -1) {
                    this.hideItem(item)
                    continue;
                }
                if(!!keyword && (data.name || data.title).toLowerCase().indexOf(keyword) === -1) {
                    this.hideItem(item)
                    continue;
                }
                this.showItem(item);
                found++;
            }
            console.log(keyword, category)
            this.modulesListNoResultsNode.style.display = found === 0 ? '' : 'none';
            resolve();
        });
    }
    async search(category = '', keyword = '') {
        if (this.settings.searchMode === 'local') {
            return this.#searchLocal(category, keyword);
        }
    }

    async createCategorized() {

        this.setData(this.settings.data);
        this.createRoot();
        this.createSearchGUI();
        this.renderModulesCategorized();

        return new Promise(resolve => {
            resolve(this)
        })
    }
    async create() {
        this.setData(this.settings.data);
        this.createRoot();
        this.createCategoriesMenu();
        this.renderModules();
        this.createSearchGUI();
        return new Promise(resolve => {
            resolve(this)
        })
    }


}
