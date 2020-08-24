<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">


        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <?php
                $logo_name = $params['id'];

                if (isset($params['logo-name'])) {
                    $logo_name = $params['logo-name'];
                } else if (isset($params['logo_name'])) {
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
                        max-width: 300px;
                        background-color: #e1e2e4;
                        padding: 15px;
                        min-height: 50px;
                        margin-bottom: 20px;
                    }

                    .the-image[src=''],
                    .the-image-inverse[src=''] {
                        width: 100%;
                        background-color: #e1e2e4;
                    }

                    #sizeslider {
                        width: 135px;
                    }
                </style>

                <script>mw.require('tools/images.js');</script>
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
                    });
                </script>
                <script>
                    $(document).ready(function () {
                        var $size = $("#size"),
                            $size_slider = $("#size-slider"),
                            $imagesizeval = $("#imagesizeval");


                        if ("<?php print $size; ?>" == 'auto') {
                            $imagesizeval.html('auto');
                            $("#auto_scale_logo").attr("checked", true);
                        }
                        else {
                            $imagesizeval.html($size_slider.val());
                            $("#auto_scale_logo").attr("checked", false);
                        }


                        $size_slider.on('input change', function () {
                            $size.val(this.value)
                            $("#auto_scale_logo")[0].checked = false;
                            $imagesizeval.html(this.value + 'px');
                            $size.trigger('change')
                        });

                        $("#auto_scale_logo").on('change', function () {
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
                <script>
                    $(document).ready(function () {
                        mw.top().on('imageSrcChanged', function (e, node, url) {
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

                <div class="module-live-edit-settings module-logo-settings" id="module-logo-settings">
                    <input type="hidden" class="mw_option_field" name="logoimage" id="logoimage"/>
                    <input type="hidden" class="mw_option_field" name="logoimage_inverse" id="logoimage_inverse"/>

                    <div class="logo-module-types">
                        <div class="form-group">
                            <label class="control-label mb-3">Choose Logo type</label>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="logotype1" class="mw_option_field custom-control-input" <?php if ($logotype == 'image'){ ?>checked<?php } ?> name="logotype" value="image">
                                <label class="custom-control-label" for="logotype1"><?php _e('Image Logo'); ?><br/>
                                    <small class="text-muted">Upload your logo image in .JPG or .PNG format</small>
                                </label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="logotype2" class="mw_option_field custom-control-input" <?php if ($logotype == 'text'){ ?>checked<?php } ?> name="logotype" value="text">
                                <label class="custom-control-label" for="logotype2"><?php _e('Text Logo'); ?><br/>
                                    <small class="text-muted">Type your brand and choose font size and style</small>
                                </label>
                            </div>

                            <div class="custom-control custom-radio d-none">
                                <input type="radio" id="logotype3" class="mw_option_field custom-control-input" <?php if ($logotype == 'both' or $logotype == false){ ?>checked<?php } ?> name="logotype" value="both">
                                <label class="custom-control-label" for="logotype3"><?php _e('Both'); ?><br/>
                                    <small class="text-muted">Type your brand and choose font size</small>
                                </label>
                            </div>
                        </div>
                    </div>


                    <hr class="thin my-4"/>

                    <div class="js-logo-image-holder">
                        <div class="form-group">
                            <label class="control-label">Main Logo</label>
                            <small class="text-muted d-block mb-2">This logo image will appear every time</small>
                        </div>

                        <div class="image-row">
                            <div class="the-image-holder">
                                <img style="display: none;" src="<?php print $logoimage ?>" id="logo-image-edit">
                                <img src="<?php if ($logoimage): ?><?php echo thumbnail($logoimage, 200); ?><?php endif; ?>" class="the-image" alt="" <?php if ($logoimage != '' and $logoimage != false): ?><?php else: ?>style="display:block;"<?php endif; ?> />
                            </div>

                            <div>
                                <span class="btn btn-primary btn-rounded btn-sm" id="upload-image">Upload Image</span>
                                <a href="javascript:mw_admin_logo_upload_browse_existing()" class="btn btn-outline-primary btn-rounded btn-sm"><?php _e('Browse Uploaded'); ?></a>
                                <?php if ($logotype == 'both' or $logotype == 'image' or $logotype == false): ?>
                                    <a class="btn btn-outline-primary btn-rounded btn-sm" onclick="mw.edit_logo_image_crop()" href="javascript:void(0);">Edit image</a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($alt_logo == 'true'): ?>
                            <br/>

                            <div class="form-group">
                                <label class="control-label">Alternative Logo</label>
                                <small class="text-muted d-block mb-2">For example we are using the alternative logo when we have a sticky navigation</small>
                            </div>

                            <div class="image-row">
                                <div class="the-image-holder">
                                    <img src="<?php if ($logoimage_inverse): ?><?php echo thumbnail($logoimage_inverse, 200); ?><?php endif; ?>" class="the-image-inverse" alt="" <?php if ($logoimage_inverse != '' and $logoimage_inverse != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                                </div>

                                <div>
                                    <span class="btn btn-primary btn-rounded btn-sm" id="upload-image-inverse">Upload Image</span>
                                    <a href="javascript:mw_admin_logo_upload_browse_existing('true')" class="btn btn-outline-primary btn-rounded btn-sm"><?php _e('Browse Uploaded'); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr class="thin"/>


                        <div class="form-group">
                            <label class="control-label">Scale the logo image</label>

                            <div>
                                <p class="mb-1"><?php _e('Image size'); ?> - <span id="imagesizeval"></span></p>
                                <div class="range-slider">
                                    <input name="size-slider" id="size-slider" class="mw-ui-field-range" max="200" min="20" type="range" value="<?php print $size; ?>">
                                </div>
                                <input name="size" id="size" type="hidden" class="mw_option_field" value="<?php print $size; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked="" id="auto_scale_logo" value="pending">
                                <label class="custom-control-label" for="auto_scale_logo"><?php _e('Set auto logo size'); ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="js-logo-text-holder">
                        <div class="form-group">
                            <label class="control-label">Design your logo</label>
                            <small class="text-muted d-block mb-2">Chooose fornt size for your logo</small>

                            <select id="google-fonts" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true" name="font_family">
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

                        <div class="form-group">
                            <script>mw.require('editor.js')</script>
                            <script>
                                $(document).ready(function () {
                                    mweditor = new mw.Editor({
                                        selector: '#text',
                                        mode: 'div',
                                        smallEditor: false,
                                        minHeight: 150,
                                        maxHeight: '70vh',
                                        controls: [
                                            [
                                                'undoRedo', '|',
                                                {
                                                    group: {
                                                        icon: 'mdi mdi-format-bold',
                                                        controls: ['bold', 'italic', 'underline', 'strikeThrough']
                                                    }
                                                },

                                                '|', 'link', 'unlink', 'wordPaste'
                                            ],
                                        ]
                                    });
                                });
                            </script>

                            <textarea class="mw_option_field form-control" placeholder="<?php _e('Enter Text'); ?>" row="5" name="text" id="text"><?php print $text; ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates" simple="true"/>
            </div>
        </div>
    </div>
</div>