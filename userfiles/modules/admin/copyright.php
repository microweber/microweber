<?php if(mw()->ui->powered_by_link_enabled()){ ?>
<div class="row copyright mt-3">
    <div class="col-12">
        <p class="text-muted text-center small"><?php _e("Open-source website builder and CMS {{app_name}}");?> <?php echo date('Y'); ?>. Version: <a href="https://github.com/microweber/microweber/blob/master/CHANGELOG.md" target="_blank" class="text-muted"><?php echo MW_VERSION; ?></a></p>
    </div>
</div>
<?php  } ?>