<script type="text/javascript">
    mw.top().dialog.get().resize(1000);
</script>
<div>
    <div class="mb-3 card-in-live-edit">
       <div class="row">
           <div class="px-0">
               <?php $module_info = module_info('shop/admin'); ?>
               <h3 class="main-pages-title"><?php _e($module_info['name']); ?></h3>
           </div>

           <div class=" ">

               <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
                   <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#products">  <?php _e('Products'); ?></a>
                   <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#settings">  <?php _e('Settings'); ?></a>

                   <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
               </nav>

               <div class="tab-content py-3">

                   <div class="tab-pane fade show active" id="products">

                       <div>
                           <livewire:admin-products-list open-links-in-modal="true" />
                           <livewire:admin-content-bulk-options />
                       </div>

                   </div>

                   <div class="tab-pane fade" id="settings">
                       <livewire:microweber-module-shop::shop-settings :moduleId="$moduleId" moduleType="shop" />
                   </div>

                  <div class="tab-pane fade" id="templates">

                      <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" moduleType="shop" />

                   </div>

               </div>

           </div>
       </div>
    </div>

</div>
