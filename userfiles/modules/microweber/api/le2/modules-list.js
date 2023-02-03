

class ModulesList {
    constructor( options = {} ) {
        const defaults = {
            mode: 'local',
            document: document,
            searchMode: 'local',
            data: [],
            skin: 'defaultModules' // 'defaultModules' | 'defaultLayouts'
        }

        this.settings = Object.assign({}, defaults, options);
        this.document = this.settings.document;
    }
    #data = null;
    #_e = {};
    
    on(e, f){ this.#_e[e] ? this.#_e[e].push(f) : (this.#_e[e] = [f]) };
    dispatch(e, f){ this.#_e[e] ? this.#_e[e].forEach(c => { c.call(this, f); }) : ''; };

    getData() {
        return this.#data;
    }

    setData(data, trigger = true) {
        this.#data = data;
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
            let cats = item.categories;
            if (typeof cats === 'string') {
                cats = cats.split(',');
            }
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
        nav.innerHTML = this.categories.map(c => '<li data-category="${c}">${c}</li>').join('');
        nav.addEventListener('click', e => {
            if (e.target.nodeName === 'LI') {
                this.selectCategory(e.target.dataset.category);
            }
        })
        this.rootShadow.appendChild(nav);
    }

    selectedCategory = '';
    selectCategory(category = '') {
        this.selectedCategory = category;
        this.search(category)
        this.dispatch('categorySelect', category);
    }
    #createRoot() {
        this.root = this.document.crateElement('div');
        this.root.className = 'modules-list modules-list-' + this.settings.skin;
        this.rootShadow = this.root.attachShadow({mode: 'open'});
    }
    #renderModule(data) {
        const moduleItem = this.document.crateElement('div');
        moduleItem.className = 'modules-list-block-item';
        moduleItem.innerHTML = `
            <div class="modules-list-block-item-picture" style="background-image: ${data.icon}"></div>
            <div class="modules-list-block-item-title">${data.name || data.title}</div>
            <div class="modules-list-block-item-description">${data.description}</div>
        `;
        moduleItem.addEventListener('click', e => {
            e.stopPropagation();
            this.dispatch('moduleSelected', {data, element: moduleItem});
        });
        moduleItem.$data = data;
        return moduleItem;
    }

    #modulesNodes = [];
    
    #renderModules() {
        this.modulesList = this.document.crateElement('div');
        this.modulesList.className = 'modules-list-block'
        const data = this.getData();
        let i = 0, length = data.length;
        for( ; i < length; i++) {
            const moduleItem = this.#renderModule(data[i]);
            this.#modulesNodes.push(moduleItem);
            this.modulesList.appendChild(moduleItem)
        }
        this.rootShadow.appendChild(this.modulesList)
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

    #createSearchGUI() {
        this.searchBlock = this.document.createElement('div');
        this.searchField = this.document.createElement('input');
        this.searchBlock.className = 'modules-list-search-block';
        this.searchField.className = 'modules-list-search-field';
        this.searchBlock.appendChild(this.searchField);
        this.rootShadow.appendChild(this.searchBlock);
        this.searchField.addEventListener('input', e => this.search(this.searchField.value))
    }
    
    async #searchLocal(category, keyword) {
        if(keyword) {
            keyword = keyword.trim();
        }
        return new Promise(resolve => {
            let i = 0, length = this.#modulesNodes.length;
            for ( ; i < length; i++) {
                const item = this.#modulesNodes[i];
                const data = item.$data;
                let matches = true;
                if(!!category && data.categories.indexOf(category) === -1) {
                    this.hideItem(item)
                    continue;
                }
                if(!!keyword && (data.name || data.title).indexOf(keyword) === -1) {
                    this.hideItem(item)
                    continue;
                }
                this.showItem(item)
            }
        })
    }
    async search(category = '', keyword = '') {
        if (this.settings.searchMode === 'local') {
            return this.#searchLocal(category, keyword);
        }
    }

    async render() {
        this.setData(this.settings.data);
        this.#createRoot();
        this.createCategoriesMenu();
        this.#renderModules();
        this.#createSearchGUI();
        return new Promise(resolve => {
            resolve(this)
        })
    }
}
