<?php if(app()->ui->powered_by_link_enabled() and app()->ui->service_links_enabled()){ ?>
<div class="row copyright mt-3 mw-100">
    <div class="col-12">
        <p class=" tblr-body-color text-center small ">  <?php echo    app()->ui->powered_by_link() ; ?>   Version:  <?php echo MW_VERSION; ?> </p>
    </div>
</div>
<?php  } ?>
