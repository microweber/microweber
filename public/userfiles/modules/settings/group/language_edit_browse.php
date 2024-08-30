<?php

$namespace = 'global';
if (isset($params['translation_namespace'])) {
    $namespace = $params['translation_namespace'];
}
$namespaceMd5 = md5($namespace);
?>


<script>

    /**
     * jQuery serializeObject
     * @copyright 2014, macek <paulmacek@gmail.com>
     * @link https://github.com/macek/jquery-serialize-object
     * @license BSD
     * @version 2.5.0
     */
    !function(e,i){if("function"==typeof define&&define.amd)define(["exports","jquery"],function(e,r){return i(e,r)});else if("undefined"!=typeof exports){var r=require("jquery");i(exports,r)}else i(e,e.jQuery||e.Zepto||e.ender||e.$)}(this,function(e,i){function r(e,r){function n(e,i,r){return e[i]=r,e}function a(e,i){for(var r,a=e.match(t.key);void 0!==(r=a.pop());)if(t.push.test(r)){var u=s(e.replace(/\[\]$/,""));i=n([],u,i)}else t.fixed.test(r)?i=n([],r,i):t.named.test(r)&&(i=n({},r,i));return i}function s(e){return void 0===h[e]&&(h[e]=0),h[e]++}function u(e){switch(i('[name="'+e.name+'"]',r).attr("type")){case"checkbox":return"on"===e.value?!0:e.value;default:return e.value}}function f(i){if(!t.validate.test(i.name))return this;var r=a(i.name,u(i));return l=e.extend(!0,l,r),this}function d(i){if(!e.isArray(i))throw new Error("formSerializer.addPairs expects an Array");for(var r=0,t=i.length;t>r;r++)this.addPair(i[r]);return this}function o(){return l}function c(){return JSON.stringify(o())}var l={},h={};this.addPair=f,this.addPairs=d,this.serialize=o,this.serializeJSON=c}var t={validate:/^[a-z_][a-z0-9_]*(?:\[(?:\d*|[a-z0-9_]+)\])*$/i,key:/[a-z0-9_]+|(?=\[\])/gi,push:/^$/,fixed:/^\d+$/,named:/^[a-z0-9_]+$/i};return r.patterns=t,r.serializeObject=function(){return new r(i,this).addPairs(this.serializeArray()).serialize()},r.serializeJSON=function(){return new r(i,this).addPairs(this.serializeArray()).serializeJSON()},"undefined"!=typeof i.fn&&(i.fn.serializeObject=r.serializeObject,i.fn.serializeJSON=r.serializeJSON),e.FormSerializer=r,r});

    //$(document).keypress(function(e) {
    //    if (e.which == 13) {
    //        searchLangauges<?php //echo $namespaceMd5;?>//();
    //    }
    //});

    function searchLangauges<?php echo $namespaceMd5;?>()
    {
        var searchText = $('.js-search-lang-text-<?php echo $namespaceMd5;?>').val();

        $('#js-language-edit-browse-content-<?php echo $namespaceMd5;?>').attr('page', 1);
        $('#js-language-edit-browse-content-<?php echo $namespaceMd5;?>').attr('search', searchText);

        mw.tools.loading('#js-language-edit-browse-content-<?php echo $namespaceMd5;?>',true)
        mw.reload_module('#js-language-edit-browse-content-<?php echo $namespaceMd5;?>', function () {
            mw.tools.loading('#js-language-edit-browse-content-<?php echo $namespaceMd5;?>',false)
        });
    }

    $(document).ready(function () {

        $('.js-import-language-translations').click(function () {
            $('.js-import-language-translations').html('Importing...');
            $.ajax({
                type: "POST",
                url: "<?php echo route('admin.language.import_missing_translations'); ?>",
            }).done(function (resp) {
                mw.notification.success('<?php _ejs('Translations are imported'); ?>');
                location.reload();
            });
        });

        $('.js-search-lang-text-<?php echo $namespaceMd5;?>').off('input');
        $('.js-search-lang-text-<?php echo $namespaceMd5;?>').on('input', function () {
            mw.on.stopWriting(this,function() {
                searchLangauges<?php echo $namespaceMd5;?>();
            });
        });

    });


</script>
<style scoped>
    .lang_textarea_key {
        display: inline-block;
        border: none;
        overflow-y: auto;
        resize: both;
    }
</style>


<?php




if (\MicroweberPackages\Translation\Models\TranslationText::where('translation_locale', mw()->lang_helper->current_lang())->count() == 0):
?>
<div class="col-xxl-8 col-xl-10 col-12 mx-auto alert alert-warning mb-3">
<?php _e('Translations not found in database. Do you wish to import translations? '); ?>
    <br /><br />
    <button type="button" class="js-import-language-translations btn btn-outline-primary btn-sm"><?php _e('Import'); ?></button>
</div>
<?php
endif;
?>

<div id="language-edit-<?php echo $namespaceMd5;?>">
    <div class="card-body px-md-3 px-1">

        <div class="col-xxl-8 col-xl-10 col-12 px-md-3 mx-auto">
            <div class="js-lang-edit-form-messages"></div>

            <div class="input-icon mb-3">
                <input type="text" value="<?php if(isset($filter['search'])) { echo $filter['search']; } ?>" class="form-control js-search-lang-text-<?php echo $namespaceMd5;?>" placeholder="<?php _e('Enter a word or phrase'); ?>"/>

                <span class="input-icon-addon">
              <!-- Download SVG icon from http://tabler-icons.io/i/search -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
            </span>
            </div>

            <div class="d-flex justify-content-center align-items-center my-3">

                <div>
                    <a type="button" onClick="importTranslation('<?php echo $namespaceMd5;?>')" class="btn btn-sm" data-bs-toggle="tooltip" aria-label="Import" data-bs-original-title="Import">
                        <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor" height="20" viewBox="0 96 960 960" width="20"><path d="M260 896q-91 0-155.5-63T40 679q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760 536q69 8 114.5 59.5T920 716q0 75-52.5 127.5T740 896H520q-33 0-56.5-23.5T440 816V610l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480 336q-83 0-141.5 58.5T280 536h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Zm220-280Z"/></svg>
                    </a>
                    <a type="button" onClick="exportTranslation('<?php echo $namespace;?>')" class="btn btn-sm " data-bs-toggle="tooltip" aria-label="Export" data-bs-original-title="Export">
                        <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor" height="20" viewBox="0 96 960 960" width="20"><path d="M260 896q-91 0-155.5-63T40 679q0-78 47-139t123-78q17-72 85-137t145-65q33 0 56.5 23.5T520 340v242l64-62 56 56-160 160-160-160 56-56 64 62V340q-76 14-118 73.5T280 536h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h480q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-48-22-89.5T600 376v-93q74 35 117 103.5T760 536q69 8 114.5 59.5T920 716q0 75-52.5 127.5T740 896H260Zm220-358Z"/></svg>
                    </a>

                </div>
            </div>

        </div>
        <div class="row align-items-center pt-0">
            <div class="text-center mb-3">
                <small class="text-muted d-block"><?php _e('Translate the fields to different languages'); ?></small>
            </div>
            <div>
               <module id="js-language-edit-browse-content-<?php echo $namespaceMd5;?>" namespace="<?php echo $namespace; ?>" type="settings/group/language_edit_browse_content" />
            </div>
        </div>
    </div>
</div>

