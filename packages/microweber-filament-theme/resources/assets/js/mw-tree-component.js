document.addEventListener('alpine:init', () => {
    Alpine.data('mwTreeFormComponent', function({ state, options = {}, params = {} }) {
        return {
            state,

            async init() {
                // Guard against double-mount: this field is ->live(), so a form
                // re-render can re-run init(); without this the tree widget would
                // build a SECOND tree into the same container.
                const container = document.getElementById(`mw-tree-edit-content-${options.suffix}`);
                if (!container || container.dataset.mwTreeBuilt === '1') {
                    return;
                }
                container.dataset.mwTreeBuilt = '1';

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
                });
            }
        };
    });
});
