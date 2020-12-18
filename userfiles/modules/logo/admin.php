<?php only_admin_access(); ?>

<?php



$logo_name =  $params['id'];

if(isset($params['logo-name'])){
    $logo_name = $params['logo-name'];
} else if(isset($params['logo_name'])){
    $logo_name = $params['logo_name'];
}


$logoimage = get_option('logoimage', $logo_name);
$logoimage_inverse = get_option('logoimage_inverse', $logo_name);
$text = get_option('text', $logo_name);
$font_family = get_option('font_family', $logo_name);
$font_size = get_option('font_size', $logo_name);
if ($font_size == false) {
    $font_size = 30;
}
$logotype = get_option('logotype', $logo_name);

if (!$logotype) {
    $logotype = 'image';
}

$size = get_option('size', $logo_name);
if ($size == false or $size == '') {
    $size = 60;
}

$alt_logo = false;
if (isset($params['data-alt-logo'])) {
    $alt_logo = $params['data-alt-logo'];
}
?>

<style>
    #module-logo-settings,
    #module-logo-settings * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    #hello_world_module {
        padding: 20px;
    }

    #hello_world_module label {
        display: block;
        padding: 8px 0 3px;
    }

    #font-and-text {
        width: 100%;
    }

    #font-and-text, #font-and-text * {
        vertical-align: middle;
    }

    .p-l-10 {
        padding-left: 10px;
    }

    .m-t-20 {
        margin-top: 20px;
    }

    .m-t-15 {
        margin-top: 15px;
    }

    .image-row,
    .image-row * {
        vertical-align: middle;
    }

    .the-image-holder {
        width: 100%;
    }

    .the-image-holder .upload-image {
        width: 33px;
        height: 33px;
        -webkit-border-radius: 100%;
        -moz-border-radius: 100%;
        border-radius: 100%;
        padding: 0;
        margin-top: -65px;
        margin-left: 5px;
        text-align: center;
    }

    .the-image,
    .the-image-inverse {
        display: block;
    }

    .the-image,
    .the-image-inverse {
        margin-right: 12px;
        max-width: 100%;
        background-color: #eee;
    }

    .the-image[src=''],
    .the-image-inverse[src=''] {
        width: 100%;
        background: #eee url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAWCAYAAADXYyzPAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAM5SURBVEiJnZbfax1FFMc/Z/Ymt8WC1Zo29JcSEaTQYNRkz+TBPpTGXvGlDwoqSOsf4N/QP6Q+GFB8qaDoi0JB5IaZRAWpBbFSa00F0ZikDQnRZI8Pd+9l7969yeK+7MycM9/PzNlzZlbMjLpPmqaHnHPvgo1WTRMRcr0PQgi399JytamAc+5lYCgUeobzaZome2k1ip25uQvy8OHGi2Y2mg+thxB+APDevwScrYpQF1owjYvIa8BHw8BSFPJeUzNaJZ9PRNwGZG/tE97SOIB8ZWY/5UObIYTVPrD3/gRw0cxO1dgNwAPgvojcKdiOARPA4zm0vKAM+MU5d31hYWFTVP0RsCtmdqgGdBu4AXwbQtgp+09PT0uSJGdE5IKZHR6it7y7a+83IHvDjC50BWgDT4nIZAn6J51sXQNQ1THgZEF3dWlp6S5wa2Zm5o5z7k2gF8HCJk46J62GGU/kth0zm48xrgPfea8HzXgmt/1jZh/GGNdmZ/VAlvEKcBaQorD3/n6WZZ8uLi7+oarzwBXgeEXkni+Wk4Ec7HWMZsF2I8a4qqrNLOMyMFmGdubYCRF5R1WPhxD+Ba6LSFaRI311POKcXFbVlqq+DZzOx9fM7Ju8/SowXhQoQLtDTeCSqiYhhBUzWy5DRQrgvCwOACmd7Ow6/RZj3EnTtAk8Owi1qnIaE5En8/b3ZShIB7xPLW7lPqcpHDhDyqy4ibG8u1GGmhmN4dBeCLvGhPy71oBCp26r9ABw+0ABHsnb94CdYVCRgcj9lb+bFXkweElUOJ2amnpBYoybInLLrBpaOqn+TpLkbq43WYYOgIcky6PN5shzAFmWfQms7QM14ON2u23e+3Gwiaqo9mX1Ht+tpaqHY4wbwHvAj0OgvwPXQgjL+RoumVVHVdI0vVozWdbzk20FQFWPicjTZvYYnTP8dgjhV4Bz59Rtb/M6pfIr6omqXq0B7T5bwBdJMnKz3f564JIA8N5PmNlF4OgeequSpuksMNfvABXXWm8y2AMzfqZzqdwDjgBHRWTCzMYH5/Tp7QLzYmao6nnAA40a0LrRqYJuA5+FEG72/kBUdQpoichoDYH/s6At4PPur9R/ibnHN/zDO4wAAAAASUVORK5CYII=) no-repeat center;
    }

    #sizeslider {
        width: 135px;
    }

    #google-fonts {
        width: 100%;
    }

    #module-logo-settings .mw-ui-box-content {
        padding: 20px;
    }

    .p-t-0 {
        padding-top: 0;
    }

    .p-t-30 {
        padding-top: 30px;
    }
</style>

<script>
    mw.require('tools/images.js');
</script>

<script>

    $(document).ready(function () {
        UP = mw.uploader({
            element: mwd.getElementById('upload-image'),
            filetypes: 'images',
            multiple: false
        });

        $(UP).bind('FileUploaded', function (a, b) {
            setNewImage(b.src);
            setAuto();
        });

        UPInverse = mw.uploader({
            element: mwd.getElementById('upload-image-inverse'),
            filetypes: 'images',
            multiple: false
        });

        $(UPInverse).bind('FileUploaded', function (a, b) {
            setNewImageInv(b.src);
            setAuto();
        });

        mw.$("#google-fonts option").each(function () {
            var val = $(this).attr('value');
            if (val != '') {
                mw.require('//fonts.googleapis.com/css?family=' + val + '&filetype=.css', true);
                $(this).css('fontFamily', $(this).text());
            }
        });

        mw.$("#google-fonts").bind("change", function () {
            mw.$("#text").css('fontFamily', $(this.options[this.selectedIndex]).text())
        });


    });

    function setNewImage(s) {
        mw.$("#logoimage").val(s);
        mw.$(".the-image").show().attr('src', s);
        setTimeout(function () {
            mw.$("#logoimage").trigger('change');
        }, 78)
    }

    function setNewImageInv(s) {
        mw.$("#logoimage_inverse").val(s);
        mw.$(".the-image-inverse").show().attr('src', s);
        setTimeout(function () {
            mw.$("#logoimage_inverse").trigger('change');
        }, 78)
    }

    var mw_admin_logo_upload_browse_existing = function (inverse = false) {
        var modal_id = 'mw_admin_logo_upload_browse_existing_modal<?php print $logo_name ?>' + (inverse ? '_inverse' : '');
        var dialog = mw.top().dialogIframe({
            url: '<?php print site_url() ?>module/?type=files/admin&live_edit=true&remeber_path=true&ui=basic&start_path=media_host_base&from_admin=true&file_types=images&id=mw_admin_logo_upload_browse_existing_modal<?php print $logo_name ?>&from_url=<?php print site_url() ?>',
            title: "Browse pictures",
            id: modal_id,
            onload: function () {
                this.iframe.contentWindow.mw.on.hashParam('select-file', function () {
                    mw.notification.success('<?php _ejs('Logo image selected') ?>');
                    if (inverse) {
                        setNewImageInv(this);
                    } else {
                        setNewImage(this);
                    }
                    dialog.remove();
                });
            },
            height: 'auto',
            autoHeight: true
        })
    }

</script>

<script>
    function showLogoType() {
        if ($('input[name="logotype"]:checked').val() == 'image') {
            $('.js-logo-image-holder').show();
            $('.js-logo-text-holder').hide();
        } else if ($('input[name="logotype"]:checked').val() == 'text') {
            $('.js-logo-image-holder').hide();
            $('.js-logo-text-holder').show();
        }
    }

    $(document).ready(function () {
        $('#google-fonts option[value="<?php print $font_family; ?>"]').prop('selected', true);

        showLogoType();
        $('input[name="logotype"]').each(function () {
            $(this).parent().parent().on("click", function () {
                setTimeout(function () {
                    showLogoType();
                }, 78)
            });
        });


        mw.editor({
            element: '#text',
            height: 200,
            width: '100%',
            hideControls: ['format', 'ol', 'ul', 'div', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'link', 'unlink', 'remove_formatting']
        })
    });
</script>

<script>
    $(document).ready(function () {
        var $size = $("#size"),
            $size_slider = $("#size-slider"),
            $imagesizeval = $("#imagesizeval");


        if ("<?php print $size; ?>" == 'auto') {
            $imagesizeval.html('auto');
            $("#order_status1").attr("checked", true);
        }
        else {
            $imagesizeval.html($size_slider.val());
            $("#order_status1").attr("checked", false);
        }


        $size_slider.on('input change', function () {
            $size.val(this.value)
            $("#order_status1")[0].checked = false;
            $imagesizeval.html(this.value + 'px');
            $size.trigger('change')
        });

        $("#order_status1").on('change', function () {
            if (this.checked) {
                setAuto();
            }
            else {
                var val1 = $size_slider.val();
                $size.val(val1).trigger('change');
                $imagesizeval.html(val1 + 'px');
            }
        });

        setAuto = function () {
            $imagesizeval.html('auto');
            $size.val('auto');
            $size.trigger('change');
        };
    });
</script>

<div class="module-live-edit-settings module-logo-settings" id="module-logo-settings">
    <input type="hidden" class="mw_option_field" name="logoimage" id="logoimage"/>
    <input type="hidden" class="mw_option_field" name="logoimage_inverse" id="logoimage_inverse"/>

    <div class="logo-module-types">
        <div class="mw-ui-row-nodrop">
            <label class="mw-ui-check mw-ui-check-lg">
                <div class="mw-ui-col" style="width: 40px;">
                    <input type="radio" class="mw_option_field" <?php if ($logotype == 'image'){ ?>checked=""<?php } ?> name="logotype" value="image">
                    <span></span>
                </div>
                <div class="mw-ui-col type-title">
                    <span><?php _e('Image Logo'); ?><br/><small>Upload your logo image in (JPG, PNG formats)</small></span>
                </div>
            </label>
        </div>

        <div class="mw-ui-row-nodrop">
            <label class="mw-ui-check mw-ui-check-lg">
                <div class="mw-ui-col" style="width: 40px;">
                    <input type="radio" class="mw_option_field" <?php if ($logotype == 'text'){ ?>checked=""<?php } ?> name="logotype" value="text">
                    <span></span>
                </div>

                <div class="mw-ui-col type-title">
                    <span><?php _e('Text Logo'); ?><br/><small>Type your brand and choose font size</small></span>
                </div>
            </label>
        </div>

        <div class="mw-ui-row-nodrop">
            <label class="mw-ui-check">
                <div class="mw-ui-col" style="width: 40px;">
                    <input type="radio" class="mw_option_field" <?php if ($logotype == 'both' or $logotype == false){ ?>checked=""<?php } ?> name="logotype" value="both">
                    <span></span>
                </div>
                <div class="mw-ui-col type-title">
                    <span><?php _e('Both'); ?></span>
                </div>
            </label>
        </div>
    </div>


    <hr/>


    <script>



        $(document).ready(function(){


            mw.top().on('imageSrcChanged', function(e, node, url){
                setNewImage(url);
                setAuto();
            });

        });

        mw.edit_logo_image_crop = function () {
            mw.top().image.currentResizing = $('#logo-image-edit');
            mw.top().image.settings();
            return false;

        }

    </script>
    <div class="js-logo-image-holder">
        <div class="mw-ui-field-holder p-t-0">
            <label class="mw-ui-label">Main Logo
            </label>
        </div>

        <div class="mw-ui-row image-row">
            <div class="mw-ui-col">
                <div class="the-image-holder">

                    <img style="display: none;" src="<?php print $logoimage ?>" id="logo-image-edit">

                    <img src="<?php if ($logoimage) {
                        echo thumbnail($logoimage, 200);
                    } else {
                        echo thumbnail('', 200);
                    } ?>" class="the-image"  alt="" <?php if ($logoimage != '' and $logoimage != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                </div>
            </div>

            <div class="mw-ui-col m-t-15">
                <span class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium mw-ui-btn-rounded" id="upload-image">Upload Image</span>
                <a href="javascript:mw_admin_logo_upload_browse_existing()" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded"><?php _e('Browse Uploaded'); ?></a>
                <?php if ($logotype == 'both' or $logotype == 'image' or $logotype == false){ ?> <a    class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded" onclick="mw.edit_logo_image_crop()" href="javascript:void(0);"  >Edit image</a> <?php } ?>            </div>
        </div>
        <hr/>

        <div style="clear: both;"></div>

        <?php if ($alt_logo == 'true'): ?>
            <div class="mw-ui-field-holder p-t-0">
                <label class="mw-ui-label">Alternative Logo</label>
            </div>

            <div class="mw-ui-row image-row">
                <div class="mw-ui-col">
                    <div class="the-image-holder">
                        <img src="<?php if ($logoimage_inverse) {
                            echo thumbnail($logoimage_inverse, 200);
                        } else {
                            echo thumbnail('', 200);
                        } ?>" class="the-image-inverse" alt="" <?php if ($logoimage_inverse != '' and $logoimage_inverse != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                    </div>
                </div>

                <div class="mw-ui-col m-t-15">
                    <span class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium mw-ui-btn-rounded" id="upload-image-inverse">Upload Image</span>
                    <a href="javascript:mw_admin_logo_upload_browse_existing('true')" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded"><?php _e('Browse Uploaded'); ?></a>
                </div>
            </div>
            <hr/>
        <?php endif; ?>

        <div class="mw-flex-row">
            <div class="mw-flex-col-xs-12">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Use automatic scale of the image</label>
                </div>

                <label class="mw-ui-check"><input type="checkbox" checked="" id="order_status1" value="pending">
                    <span></span><span><?php _e('Auto logo size'); ?></span>
                </label>
            </div>

            <div class="mw-flex-col-xs-12 m-t-20">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><span><?php _e('Image size'); ?></span> - <b id="imagesizeval"></b></label>
                    <div class="range-slider">
                        <input name="size-slider" id="size-slider" class="mw-ui-field-range" max="200" min="20" type="range" value="<?php print $size; ?>">
                    </div>
                    <input name="size" id="size" type="hidden" class="mw_option_field" value="<?php print $size; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="js-logo-text-holder">
        <div class="mw-ui-field-hoilder" style="padding-bottom: 20px;">
            <select id="google-fonts" class="mw-ui-field mw_option_field" name="font_family">
                <option value=""><?php _e('Choose font'); ?></option>
                <option value="Aclonica">Aclonica</option>
                <option value="Allan">Allan</option>
                <option value="Annie+Use+Your+Telescope">Annie Use Your Telescope</option>
                <option value="Anonymous+Pro">Anonymous Pro</option>
                <option value="Allerta+Stencil">Allerta Stencil</option>
                <option value="Allerta">Allerta</option>
                <option value="Amaranth">Amaranth</option>
                <option value="Anton">Anton</option>
                <option value="Architects+Daughter">Architects Daughter</option>
                <option value="Arimo">Arimo</option>
                <option value="Artifika">Artifika</option>
                <option value="Arvo">Arvo</option>
                <option value="Asset">Asset</option>
                <option value="Astloch">Astloch</option>
                <option value="Bangers">Bangers</option>
                <option value="Bentham">Bentham</option>
                <option value="Bevan">Bevan</option>
                <option value="Bigshot+One">Bigshot One</option>
                <option value="Bowlby+One">Bowlby One</option>
                <option value="Bowlby+One+SC">Bowlby One SC</option>
                <option value="Brawler">Brawler</option>
                <option value="Buda:300">Buda</option>
                <option value="Cabin">Cabin</option>
                <option value="Calligraffitti">Calligraffitti</option>
                <option value="Candal">Candal</option>
                <option value="Cantarell">Cantarell</option>
                <option value="Cardo">Cardo</option>
                <option value="Carter One">Carter One</option>
                <option value="Caudex">Caudex</option>
                <option value="Cedarville+Cursive">Cedarville Cursive</option>
                <option value="Cherry+Cream+Soda">Cherry Cream Soda</option>
                <option value="Chewy">Chewy</option>
                <option value="Coda">Coda</option>
                <option value="Coming+Soon">Coming Soon</option>
                <option value="Copse">Copse</option>
                <option value="Corben:700">Corben</option>
                <option value="Cousine">Cousine</option>
                <option value="Covered+By+Your+Grace">Covered By Your Grace</option>
                <option value="Crafty+Girls">Crafty Girls</option>
                <option value="Crimson+Text">Crimson Text</option>
                <option value="Crushed">Crushed</option>
                <option value="Cuprum">Cuprum</option>
                <option value="Damion">Damion</option>
                <option value="Dancing+Script">Dancing Script</option>
                <option value="Dawning+of+a+New+Day">Dawning of a New Day</option>
                <option value="Didact+Gothic">Didact Gothic</option>
                <option value="Droid+Sans">Droid Sans</option>
                <option value="Droid+Sans+Mono">Droid Sans Mono</option>
                <option value="Droid+Serif">Droid Serif</option>
                <option value="EB+Garamond">EB Garamond</option>
                <option value="Expletus+Sans">Expletus Sans</option>
                <option value="Fontdiner+Swanky">Fontdiner Swanky</option>
                <option value="Forum">Forum</option>
                <option value="Francois+One">Francois One</option>
                <option value="Geo">Geo</option>
                <option value="Give+You+Glory">Give You Glory</option>
                <option value="Goblin+One">Goblin One</option>
                <option value="Goudy+Bookletter+1911">Goudy Bookletter 1911</option>
                <option value="Gravitas+One">Gravitas One</option>
                <option value="Gruppo">Gruppo</option>
                <option value="Hammersmith+One">Hammersmith One</option>
                <option value="Holtwood+One+SC">Holtwood One SC</option>
                <option value="Homemade+Apple">Homemade Apple</option>
                <option value="Inconsolata">Inconsolata</option>
                <option value="Indie+Flower">Indie Flower</option>
                <option value="IM+Fell+DW+Pica">IM Fell DW Pica</option>
                <option value="IM+Fell+DW+Pica+SC">IM Fell DW Pica SC</option>
                <option value="IM+Fell+Double+Pica">IM Fell Double Pica</option>
                <option value="IM+Fell+Double+Pica+SC">IM Fell Double Pica SC</option>
                <option value="IM+Fell+English">IM Fell English</option>
                <option value="IM+Fell+English+SC">IM Fell English SC</option>
                <option value="IM+Fell+French+Canon">IM Fell French Canon</option>
                <option value="IM+Fell+French+Canon+SC">IM Fell French Canon SC</option>
                <option value="IM+Fell+Great+Primer">IM Fell Great Primer</option>
                <option value="IM+Fell+Great+Primer+SC">IM Fell Great Primer SC</option>
                <option value="Irish+Grover">Irish Grover</option>
                <option value="Irish+Growler">Irish Growler</option>
                <option value="Istok+Web">Istok Web</option>
                <option value="Josefin+Sans">Josefin Sans Regular 400</option>
                <option value="Josefin+Slab">Josefin Slab Regular 400</option>
                <option value="Judson">Judson</option>
                <option value="Jura"> Jura Regular</option>
                <option value="Jura:500"> Jura 500</option>
                <option value="Jura:600"> Jura 600</option>
                <option value="Just+Another+Hand">Just Another Hand</option>
                <option value="Just+Me+Again+Down+Here">Just Me Again Down Here</option>
                <option value="Kameron">Kameron</option>
                <option value="Kenia">Kenia</option>
                <option value="Kranky">Kranky</option>
                <option value="Kreon">Kreon</option>
                <option value="Kristi">Kristi</option>
                <option value="La+Belle+Aurore">La Belle Aurore</option>
                <option value="Lato:100">Lato 100</option>
                <option value="Lato:100italic">Lato 100 (plus italic)</option>
                <option value="Lato:300">Lato Light 300</option>
                <option value="Lato">Lato</option>
                <option value="Lato:bold">Lato Bold 700</option>
                <option value="Lato:900">Lato 900</option>
                <option value="League+Script">League Script</option>
                <option value="Lekton"> Lekton</option>
                <option value="Limelight"> Limelight</option>
                <option value="Lobster">Lobster</option>
                <option value="Lobster Two">Lobster Two</option>
                <option value="Lora">Lora</option>
                <option value="Love+Ya+Like+A+Sister">Love Ya Like A Sister</option>
                <option value="Loved+by+the+King">Loved by the King</option>
                <option value="Luckiest+Guy">Luckiest Guy</option>
                <option value="Maiden+Orange">Maiden Orange</option>
                <option value="Mako">Mako</option>
                <option value="Maven+Pro"> Maven Pro</option>
                <option value="Maven+Pro:500"> Maven Pro 500</option>
                <option value="Maven+Pro:700"> Maven Pro 700</option>
                <option value="Maven+Pro:900"> Maven Pro 900</option>
                <option value="Meddon">Meddon</option>
                <option value="MedievalSharp">MedievalSharp</option>
                <option value="Megrim">Megrim</option>
                <option value="Merriweather">Merriweather</option>
                <option value="Metrophobic">Metrophobic</option>
                <option value="Michroma">Michroma</option>
                <option value="Miltonian Tattoo">Miltonian Tattoo</option>
                <option value="Miltonian">Miltonian</option>
                <option value="Modern Antiqua">Modern Antiqua</option>
                <option value="Monofett">Monofett</option>
                <option value="Montserrat">Montserrat.</option>
                <option value="Molengo">Molengo</option>
                <option value="Mountains of Christmas">Mountains of Christmas</option>
                <option value="Muli:300">Muli Light</option>
                <option value="Muli">Muli Regular</option>
                <option value="Neucha">Neucha</option>
                <option value="Neuton">Neuton</option>
                <option value="News+Cycle">News Cycle</option>
                <option value="Nixie+One">Nixie One</option>
                <option value="Nobile">Nobile</option>
                <option value="Nova+Cut">Nova Cut</option>
                <option value="Nova+Flat">Nova Flat</option>
                <option value="Nova+Mono">Nova Mono</option>
                <option value="Nova+Oval">Nova Oval</option>
                <option value="Nova+Round">Nova Round</option>
                <option value="Nova+Script">Nova Script</option>
                <option value="Nova+Slim">Nova Slim</option>
                <option value="Nova+Square">Nova Square</option>
                <option value="Nunito:light"> Nunito Light</option>
                <option value="Nunito"> Nunito Regular</option>
                <option value="OFL+Sorts+Mill+Goudy+TT">OFL Sorts Mill Goudy TT</option>
                <option value="Old+Standard+TT">Old Standard TT</option>
                <option value="Open+Sans:300">Open Sans light</option>
                <option value="Open+Sans">Open Sans regular</option>
                <option value="Open+Sans:600">Open Sans 600</option>
                <option value="Open+Sans:800">Open Sans bold</option>
                <option value="Open+Sans+Condensed:300">Open Sans Condensed</option>
                <option value="Orbitron">Orbitron Regular (400)</option>
                <option value="Orbitron:500">Orbitron 500</option>
                <option value="Orbitron:700">Orbitron Regular (700)</option>
                <option value="Orbitron:900">Orbitron 900</option>
                <option value="Oswald">Oswald</option>
                <option value="Over+the+Rainbow">Over the Rainbow</option>
                <option value="Reenie+Beanie">Reenie Beanie</option>
                <option value="Pacifico">Pacifico</option>
                <option value="Patrick+Hand">Patrick Hand</option>
                <option value="Paytone+One">Paytone One</option>
                <option value="Permanent+Marker">Permanent Marker</option>
                <option value="Philosopher">Philosopher</option>
                <option value="Play">Play</option>
                <option value="Playfair+Display">Playfair Display</option>
                <option value="Podkova"> Podkova</option>
                <option value="PT+Sans">PT Sans</option>
                <option value="PT+Sans+Narrow">PT Sans Narrow</option>
                <option value="PT+Sans+Narrow:regular,bold">PT Sans Narrow (plus bold)</option>
                <option value="PT+Serif">PT Serif</option>
                <option value="PT+Serif Caption">PT Serif Caption</option>
                <option value="Puritan">Puritan</option>
                <option value="Quattrocento">Quattrocento</option>
                <option value="Quattrocento+Sans">Quattrocento Sans</option>
                <option value="Radley">Radley</option>
                <option value="Raleway:100">Raleway</option>
                <option value="Redressed">Redressed</option>
                <option value="Rock+Salt">Rock Salt</option>
                <option value="Rokkitt">Rokkitt</option>
                <option value="Ruslan+Display">Ruslan Display</option>
                <option value="Schoolbell">Schoolbell</option>
                <option value="Shadows+Into+Light">Shadows Into Light</option>
                <option value="Shanti">Shanti</option>
                <option value="Sigmar+One">Sigmar One</option>
                <option value="Six+Caps">Six Caps</option>
                <option value="Slackey">Slackey</option>
                <option value="Smythe">Smythe</option>
                <option value="Sniglet:800">Sniglet</option>
                <option value="Special+Elite">Special Elite</option>
                <option value="Stardos+Stencil">Stardos Stencil</option>
                <option value="Sue+Ellen+Francisco">Sue Ellen Francisco</option>
                <option value="Sunshiney">Sunshiney</option>
                <option value="Swanky+and+Moo+Moo">Swanky and Moo Moo</option>
                <option value="Syncopate">Syncopate</option>
                <option value="Tangerine">Tangerine</option>
                <option value="Tenor+Sans"> Tenor Sans</option>
                <option value="Terminal+Dosis+Light">Terminal Dosis Light</option>
                <option value="The+Girl+Next+Door">The Girl Next Door</option>
                <option value="Tinos">Tinos</option>
                <option value="Ubuntu">Ubuntu</option>
                <option value="Ultra">Ultra</option>
                <option value="Unkempt">Unkempt</option>
                <option value="UnifrakturCook:bold">UnifrakturCook</option>
                <option value="UnifrakturMaguntia">UnifrakturMaguntia</option>
                <option value="Varela">Varela</option>
                <option value="Varela Round">Varela Round</option>
                <option value="Vibur">Vibur</option>
                <option value="Vollkorn">Vollkorn</option>
                <option value="VT323">VT323</option>
                <option value="Waiting+for+the+Sunrise">Waiting for the Sunrise</option>
                <option value="Wallpoet">Wallpoet</option>
                <option value="Walter+Turncoat">Walter Turncoat</option>
                <option value="Wire+One">Wire One</option>
                <option value="Yanone+Kaffeesatz">Yanone Kaffeesatz</option>
                <option value="Yanone+Kaffeesatz:300">Yanone Kaffeesatz:300</option>
                <option value="Yanone+Kaffeesatz:400">Yanone Kaffeesatz:400</option>
                <option value="Yanone+Kaffeesatz:700">Yanone Kaffeesatz:700</option>
                <option value="Yeseva+One">Yeseva One</option>
                <option value="Zeyada">Zeyada</option>
            </select>
        </div>

        <div class="mw-ui-field-hoilder">
            <textarea class="mw_option_field mw-ui-field" placeholder="<?php _e('Enter Text'); ?>" row="5" name="text" id="text"><?php print $text; ?></textarea>
        </div>
    </div>
</div>

<module type="admin/modules/templates" simple="true"/>
