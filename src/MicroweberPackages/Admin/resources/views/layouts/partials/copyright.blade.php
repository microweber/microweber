<?php if(mw()->ui->powered_by_link_enabled() and mw()->ui->service_links_enabled()){ ?>
<div class="row copyright mt-3 mw-100">
    <div class="col-12">
        <p class=" tblr-body-color text-center small ">  <?php echo    mw()->ui->powered_by_link() ; ?>   Version:  <?php echo MW_VERSION; ?> </p>
    </div>
</div>
<?php  } ?>
