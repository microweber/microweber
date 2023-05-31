<div class="ms-4 input-icon col-xl-3 col-sm-5 col-12  mb-3 mb-md-0 ps-0">
    <div class="input-group input-group-flat ">
        <input type="text" wire:model.debounce.500ms="filters.keyword" placeholder="<?php _e("Search by keyword"); ?>..." class="form-control" autocomplete="off">
        <span class="input-group-text">
            <a href="#" class="link-secondary ms-2" data-bs-toggle="tooltip" aria-label="<?php _e("Search filter")  ?>" data-bs-original-title="<?php _e("Search settings")  ?>"><!-- Download SVG icon from http://tabler-icons.io/i/adjustments -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M6 4v4"></path><path d="M6 12v8"></path><path d="M10 16a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M12 4v10"></path><path d="M12 18v2"></path><path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M18 4v1"></path><path d="M18 9v11"></path></svg>
            </a>
       </span>
    </div>
</div>
