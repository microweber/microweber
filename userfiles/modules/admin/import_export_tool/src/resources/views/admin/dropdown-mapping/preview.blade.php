<div>

    <style>
        table {
            width:100%;
        }
        table > table {
            padding:0px;
            margin:0px;
        }
        .table > :not(caption) > * > * {
            padding: 2px 11px !important;
        }
        .tags {

        }
        .tag_key {
            padding-left:5px;
        }
        .tag_value {
            color: #00168e;
            line-height: 42px !important;
        }
        .tag_value .value {
            color: #000;
        }
        .js-dropdown-select-wrapper.active {
            background: rgb(246, 246, 246);
            padding: 7px;
            border-radius: 6px;
            margin-top: 3px;
            line-height: 22px;
            border-bottom: 4px solid #00000042;
        }
    </style>

    {!! $dropdowns !!}

</div>
