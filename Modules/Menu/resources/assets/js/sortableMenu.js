export default function sortableMenu() {
    return {
        async init() {


            const collectTreeElements = target => {
                if (!target) {
                    console.log('target is not defined')
                    return [];
                }
                var closestMenuId = 0;
                var closestMenuIdElement = target.closest('[data-menu-id]');
                if (closestMenuIdElement) {
                    closestMenuId = closestMenuIdElement.getAttribute('data-menu-id');
                }


                return Array.from(closestMenuIdElement.querySelectorAll('li')).map(node => {
                    const parent = node.parentNode.closest('li');
                    return {
                        id: node.dataset.itemId,
                        parentId: parent ? parent.dataset.itemId : closestMenuId
                    }
                });
            }

            var _orderChangeHandleTimeout = null;

            var _orderChangeHandle = function (e, ui) {
                clearTimeout(_orderChangeHandleTimeout);
                _orderChangeHandleTimeout = setTimeout(function () {
                    var result = collectTreeElements(e.target);
                    result = {'items': result};



                    $.post(route('api.menu.item.reorder'), result, function () {
                        if (mw.notification) {
                            mw.notification.success('Menu changes are saved');
                        }
                    });


                }, 100);
            };


            var sortableLists = $('ul', '.admin-menu-items-holder');

            for (var i = 0; i < sortableLists.length; i++) {

                $(sortableLists[i]).nestedSortable({
                    items: ".menu_element",
                    listType: 'ul',
                    //handle:'.menu_element',
                    start: function () { // firefox triggers click when drag ends
                        // scope._disableClick = true;
                    },
                    stop: function () {
                        //  setTimeout(() => {scope._disableClick = false;}, 78)
                    },
                    update: function (e, ui) {
                        _orderChangeHandle(e, ui);
                    }
                });


            }


            // //onclick on .menu_element
            var menuElements = document.querySelectorAll('.admin-menu-items-holder .menu_element_link');
            for (var i = 0; i < menuElements.length; i++) {
                if (menuElements[i].classList.contains('binded-click')) {
                    continue;
                }
                menuElements[i].classList.add('binded-click');


                menuElements[i].addEventListener('click', (e) => {
                    e.stopPropagation();
                    e.preventDefault();

                    var id = e.target.getAttribute('data-item-id');

                    this.$wire.mountAction('editAction', {id: id})


                });

            }

        },
        updateOrder(event) {

        }
    }
}
