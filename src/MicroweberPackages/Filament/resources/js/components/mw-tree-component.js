export default function mwTreeFormComponent({ state, options = {}, params = {} }) {
    return {
        state,



        async init() {
            const selectedData = [];
            const defaultOptions = {
                selectable: true,
                singleSelect: options.singleSelect || false,
                skip: options.skip || [],
                selectedData: options.selectedData || []
            };

            const treeOptions = {
                options: defaultOptions,
                params: params
            };


            console.log(treeOptions)




            const pagesTree = await mw.widget.tree(`#mw-tree-edit-content-${options.suffix}`, treeOptions);

            pagesTree.tree.on('selectionChange', e => {
                const items = pagesTree.tree.getSelected();
                const selectedCategories = [];
                let selectedParentPage = 0;

                items.forEach(item => {
                    if (item.type === 'category') {
                        selectedCategories.push(item.id);
                    }
                    if (item.type === 'page') {
                        selectedParentPage = item.id;
                    }
                });

                this.state.parent = selectedParentPage;
                this.state.categoryIds = selectedCategories;
            })
        }
    }
}
