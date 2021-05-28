<?php
$randomId = uniqid();
?>

<div class="card-header">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="">
        <h6 class="title"><?php _e('Search') ?></h6>
        <i class="fa fa-chevron-down" style="float:right;margin-top:-18px;"></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="js-content-search" value="{{$search}}" placeholder="<?php _e('Search');?>" />
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-icon pl-3 pr-2" onclick="__contentSearch()"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).keypress(function(e) {
        if(e.which == 13) {
            __contentSearch();
        }
    });

    function __contentSearch() {
        var keywordField = document.getElementById("js-content-search");

        var redirectFilterUrl = getUrlAsArray();

        redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'search', keywordField.value);

        mw.spinner({
            element: $('#{{$moduleId}}'),
            size:"500px",
            decorate: true
        }).show();

        $('#{{$moduleId}}').attr('ajax_filter', encodeDataToURL(redirectFilterUrl));
        mw.reload_module('#{{$moduleId}}');
        window.history.pushState('{{ URL::current() }}', false, '?' + encodeDataToURL(redirectFilterUrl));

        //window.location = "{!! $searchUri !!}" + keywordField.value;
    }
</script>
