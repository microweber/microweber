<style>
    .badge-dropdown {
        background: #ffffff;
        padding: 20px;
        position: absolute;
        z-index: 15;
        box-shadow: 0px 4px 12px #00000054;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        border-top-right-radius: 4px;
        border-top:4px solid #4592ff;
        width:300px;
        margin-top:10px;
        visibility:hidden;
        opacity:0;
        transition: 0.3s;
        transform: scale(0);
        transform-origin: center top;
    }
    .badge-dropdown.active {
        visibility:visible;
        transform: scale(1);
        opacity:1;
    }

    .badge-dropdown::after {
        content: ' ';
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 0 10px 10px 10px;
        border-color: transparent transparent #4592ff transparent;
        display: inline-block;
        vertical-align: middle;
        position: absolute;
        top: -13px;
    }

    .badge-filter-item {
        background: #fff;
        color: #757575;
        border-radius: 15px;
        padding: 5px 6px;
        margin: 0px 12px;
        font-size: 12px;
    }
</style>

<livewire:admin-products-list />
