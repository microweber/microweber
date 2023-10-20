<div class="dropdown content-card-blade-dots-menu-wrapper">
    <a href="#" class=" dropdown-toggle content-card-blade-dots-menu" data-bs-toggle="dropdown"></a>

    <div class="dropdown-menu">
        <a  href="{{$content->editLink()}}" class="dropdown-item ps-4 js-open-in-modal">
            <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px"><g><rect fill="none" height="24" width="24"/></g><g><g><g><path d="M3,21l3.75,0L17.81,9.94l-3.75-3.75L3,17.25L3,21z M5,18.08l9.06-9.06l0.92,0.92L5.92,19L5,19L5,18.08z"/></g><g><path d="M18.37,3.29c-0.39-0.39-1.02-0.39-1.41,0l-1.83,1.83l3.75,3.75l1.83-1.83c0.39-0.39,0.39-1.02,0-1.41L18.37,3.29z"/></g></g></g></svg>
            <?php _e("Edit") ?>
        </a>
        <a href="{{$content->link()}}?editmode=y" target="_top" class="dropdown-item ps-4">
            <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg>

            <?php _e("Live Edit") ?>

        </a>

        <?php if(!$content->is_deleted): ?>
        <a href="javascript:mw.admin.content.delete('{{ $content->id }}');" class="dropdown-item ps-4 text-danger js-delete-content-btn-{{ $content->id }}">
            <svg class="me-1 text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"/></svg>

                <?php _e("Delete") ?></a>
        <?php endif; ?>


        <?php if($content->is_deleted): ?>
        <a href="javascript:mw.admin.content.deleteForever('{{ $content->id }}');" class="dropdown-item ps-4 text-danger js-delete-content-btn-{{ $content->id }}">
            <svg class="me-1 text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"/></svg>

                <?php _e("Delete Forever") ?></a>
        <?php endif; ?>
        @if ($content->is_active < 1)
            <a href="javascript:mw.admin.content.publishContent('{{ $content->id }}');" class="dropdown-item ps-4 mw-set-content-unpublish badge badge-warning font-weight-normal"><?php _e("Unpublished") ?></a>

        @endif
    </div>
</div>
<?php
/*<div class="col-6">
@if($isInTrashed)
    @include('content::admin.content.livewire.components.trash-buttons')
@endif
</div>*/

?>
