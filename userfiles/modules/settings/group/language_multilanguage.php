<div class="card mb-7">
    <div class="card-body">
        <div class="row pt-3">
            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e("Multi-language"); ?></h5>
                <small class="text-muted"><?php _e('You can use the Multi-language module to use multiple website languages'); ?></small>
            </div>
            <div class="col-xl-9">
                <div class="card bg-azure-lt  mb-1">
                    <div class=" ">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-4">
                                    <label class="form-label"><?php _e("Activated languages"); ?></label>
                                    <small class="text-muted d-block mb-2"><?php _e("You can manage multiple languages for your website content."); ?></small>
                                    <div class="col-md-12 text-start">

                                        <?php
                                        $defaultLang = mw()->lang_helper->default_lang();
                                        $supportedLanguages = get_supported_languages();
                                        if (!empty($supportedLanguages)):
                                            $isl = 1;
                                            foreach ($supportedLanguages as $language):
                                                ?>


                                                <button type="button" class="btn btn-outline-primary mt-2" data-bs-toggle="tooltip" title="<?php echo $language['language']; ?>   <?php if (strtolower($defaultLang) == strtolower($language['locale'])): ?>
                                                   (<?php _e('Default'); ?>)  <?php endif; ?>">

                                                    <i class="flag-icon flag-icon-<?php echo get_flag_icon($language['locale']); ?> mr-2"></i> &nbsp;

                                                    <?php echo \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($language['locale']); ?>
                                                    [<?php echo $language['locale']; ?>]

                                                </button>


                                                <?php $isl++;
                                            endforeach; ?>
                                        <?php else: ?>
                                            <?php _e('No languages found.'); ?>
                                        <?php endif; ?>

                                    <?php
                                    /*    <button type="button" class="btn btn-primary mt-2" onclick="openMultilangEditModal('addLanguage')">
                                            <i class="mdi mdi-plus"></i> <?php _e('Add Language'); ?>
                                        </button>*/

                                    ?>

                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button type="button" class="btn btn-link" onclick="openMultilangEditModal('manage')">
                                            <?php _e('Language settings'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
