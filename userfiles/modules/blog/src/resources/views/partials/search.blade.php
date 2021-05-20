<div class="form-inline mt-2 mt-md-0">
    <input type="text" class="form-control mr-sm-2" value="{{$search}}" id="js-content-search" />
    <button type="submit" class="btn btn-success my-2 my-sm-0" onclick="__contentSearch()"><?php _e('Search');?></button>
</div>
<script type="text/javascript">
    function __contentSearch() {
        var keywordField = document.getElementById("js-content-search");
        window.location = "{!! $searchUri !!}" + keywordField.value;
    }
</script>
