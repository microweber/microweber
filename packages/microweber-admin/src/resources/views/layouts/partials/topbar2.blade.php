@props(['quickContentAdd' => false])


<?php
$style = '';
if (isset($isIframe) and $isIframe) {
    $style = 'position:  fixed; z-index: 100; margin-top:0px;  top:0px; width:100%; background-color: var(--tblr-bg-surface)';
}

?>
<?php if (isset($isIframe) and $isIframe) { ?>
    <div style="height: 60px; "></div>
<?php } ?>

    <div class="page-header d-print-none" style="<?php print $style; ?>">
        <div class="row g-2 align-items-center  @if(!$quickContentAdd) px-5 @endif mw-100">
            @if(!$quickContentAdd)

                <div class="col">
                    <div class="mx-1">
                        @yield('topbar2-links-left', \View::make('admin::layouts.partials.topbar2-links-left-default'))
                    </div>
                </div>
            @endif

            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">


                <?php


                    event_trigger('mw.admin.header.toolbar'); ?>

                <ul class="nav d-flex gap-2">
                    @yield('topbar2-links-right', \View::make('admin::layouts.partials.topbar2-links-right-default', ['quickContentAdd' => $quickContentAdd]))
                    <?php event_trigger('mw.admin.header.toolbar.ul'); ?>
                </ul>
            </div>
        </div>
    </div>



<div class="modal modal-blur fade hide" id="modal-add-new-admin" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="modal-title settings-title-inside">
                    Add New
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
@include('admin::layouts.partials.add-content-buttons')
            </div>
        </div>
    </div>
</div>
