
mw.iconResolver = icon => {
    if(icon === undefined) return ''

    const icons = {
        home: "<svg xmlns='http://www.w3.org/2000/svg'  fill='currentColor' height='24' viewBox='0 96 960 960' width='24'><path d='M240 856h120V616h240v240h120V496L480 316 240 496v360Zm-80 80V456l320-240 320 240v480H520V696h-80v240H160Zm320-350Z'/></svg>",
        shop: "<svg fill='currentColor' xmlns='http://www.w3.org/2000/svg' height='24' viewBox='0 96 960 960' width='24'><path d='M240 976q-33 0-56.5-23.5T160 896V416q0-33 23.5-56.5T240 336h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800 416v480q0 33-23.5 56.5T720 976H240Zm0-80h480V416h-80v80q0 17-11.5 28.5T600 536q-17 0-28.5-11.5T560 496v-80H400v80q0 17-11.5 28.5T360 536q-17 0-28.5-11.5T320 496v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480 256q-33 0-56.5 23.5T400 336ZM240 896V416v480Z'></path></svg>",
        page: "<svg xmlns='http://www.w3.org/2000/svg'  fill='currentColor' height='24' viewBox='0 96 960 960' width='24'><path d='M240 976q-33 0-56.5-23.5T160 896V256q0-33 23.5-56.5T240 176h320l240 240v480q0 33-23.5 56.5T720 976H240Zm280-520V256H240v640h480V456H520ZM240 256v200-200 640-640Z'/></svg>",
        category: "<svg xmlns='http://www.w3.org/2000/svg'  fill='currentColor' height='24' viewBox='0 96 960 960' width='24'><path d='M160 896q-33 0-56.5-23.5T80 816V336q0-33 23.5-56.5T160 256h240l80 80h320q33 0 56.5 23.5T880 416v400q0 33-23.5 56.5T800 896H160Zm0-560v480h640V416H447l-80-80H160Zm0 0v480-480Z'/></svg>",
        dynamic: "<svg xmlns='http://www.w3.org/2000/svg'   fill='currentColor' height='24' viewBox='0 96 960 960' width='24'><path d='M200 936q-33 0-56.5-23.5T120 856V296q0-33 23.5-56.5T200 216h440l200 200v440q0 33-23.5 56.5T760 936H200Zm0-80h560V456H600V296H200v560Zm80-80h400v-80H280v80Zm0-320h200v-80H280v80Zm0 160h400v-80H280v80Zm-80-320v160-160 560-560Z'/></svg>",
        trash: "<svg xmlns='http://www.w3.org/2000/svg' fill='currentColor' height='24' viewBox='0 96 960 960' width='24'><path d='M280 936q-33 0-56.5-23.5T200 856V336h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680 936H280Zm400-600H280v520h400V336ZM360 776h80V416h-80v360Zm160 0h80V416h-80v360ZM280 336v520-520Z'/></svg>",
        'add-subpage-icon-tree': "<svg xmlns='http://www.w3.org/2000/svg'  fill='currentColor' height='24' viewBox='0 96 960 960' width='24'><path d='M440 816h80V696h120v-80H520V496h-80v120H320v80h120v120ZM240 976q-33 0-56.5-23.5T160 896V256q0-33 23.5-56.5T240 176h320l240 240v480q0 33-23.5 56.5T720 976H240Zm280-520V256H240v640h480V456H520ZM240 256v200-200 640-640Z'/></svg>",
        'edit-category-icon-tree': "<svg xmlns='http://www.w3.org/2000/svg' fill='currentColor'  height='24' viewBox='0 96 960 960' width='24'><path d='M200 856h56l345-345-56-56-345 345v56Zm572-403L602 285l56-56q23-23 56.5-23t56.5 23l56 56q23 23 24 55.5T829 396l-57 57Zm-58 59L290 936H120V766l424-424 170 170Zm-141-29-28-28 56 56-28-28Z'/></svg>",
        'delete-category-icon-tree': "<svg xmlns='http://www.w3.org/2000/svg'  fill='currentColor' height='24' viewBox='0 96 960 960' width='24'><path d='M280 936q-33 0-56.5-23.5T200 856V336h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680 936H280Zm400-600H280v520h400V336ZM360 776h80V416h-80v360Zm160 0h80V416h-80v360ZM280 336v520-520Z'/></svg>",
        'add-subcategory-icon-tree': "<svg xmlns='http://www.w3.org/2000/svg'  fill='currentColor' height='24' viewBox='0 96 960 960' width='24'><path d='M560 736h80v-80h80v-80h-80v-80h-80v80h-80v80h80v80ZM160 896q-33 0-56.5-23.5T80 816V336q0-33 23.5-56.5T160 256h240l80 80h320q33 0 56.5 23.5T880 416v400q0 33-23.5 56.5T800 896H160Zm0-560v480h640V416H447l-80-80H160Zm0 0v480-480Z'/></svg>",

        'add-post-icon-tree': "<svg xmlns='http://www.w3.org/2000/svg' height='24'  fill='currentColor' viewBox='0 96 960 960' width='24'><path d='M200 936q-33 0-56.5-23.5T120 856V296q0-33 23.5-56.5T200 216h360v80H200v560h560V496h80v360q0 33-23.5 56.5T760 936H200Zm120-160v-80h320v80H320Zm0-120v-80h320v80H320Zm0-120v-80h320v80H320Zm360-80v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z'/></svg>",
        noop: "",
    }

    icons['add-product-icon-tree'] = icons.shop;

    return icons[icon] || '';
}
