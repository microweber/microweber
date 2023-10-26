<?php
$randomId = uniqid();
?>


<h6><?php _e('Search') ?></h6>


<div class="card-body px-1">
    <div class="input-group mb-3 gap-3">
        <input type="text" class="form-control js-filter-search-field" value="{{$search}}" placeholder="<?php _e('Search');?>" />
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary js-filter-search-submit btn-sm">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
            </button>
        </div>
    </div>
</div>

