<?php



  function liteness_template_colors_selectors(){
    return array(



      'primary_bg'      =>  ''
                            .'#header, #footer,'
                            .'.pagination > a.active, .mw-search-autocomplete .module-posts-template-search > ul > li.active,'
                            .'.btn-default, .btn-action, .product-price-holder .btn, .btn-action-default,'
                            .'.btn-default:hover, .btn-action:hover, .product-price-holder .btn:hover, .btn-action-default:hover,'
                            .'.btn-default:focus, .btn-action:focus, .product-price-holder .btn:focus, .btn-action-default:focus,'
                            .'.module-navigation-default li:hover > a, .module-navigation-default a.active, .module-navigation-default a.active:hover,'
                            .'.module-navigation-default a.active:active, .module-navigation-default a.active:focus,'
                            .'.module-navigation-default li:hover a, .module-navigation-default li a:hover, .module-navigation-default li a:focus,'
                            .'.module-navigation-default li:hover a.active, .module-navigation-default a.active,.module-navigation-default a.active:hover,.module-navigation-default a.active:active,.module-navigation-default a.active:focus,'
                            .'#header .pagination > a.active, #footer .pagination > a.active, .pagination > .active > a, .pagination > .active > span,'
                            .'.pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus',

      'primary_color'   => '.module-navigation-default li a, #header .module-navigation-default li a, #header .well a',

      'secondary_bg'    => "#main-menu",
      'secondary_color' => '.box-container, .box-container h1,.box-container h2,.box-container h3,.box-container h4,.box-container h5,.box-container h6,'
                           .'.box-container .h1,.box-container .h2,.box-container .h3,.box-container .h4,.box-container .h5,.box-container .h6,'
                           .'.box-container h1 > a,.box-container h2 > a,.box-container h3 > a,h4 > a,.box-container h5 > a,.box-container h6 > a,'
                           .'.box-container .h1 > a,.box-container .h2 > a,.box-container .h3 > a,.box-container .h4 > a,.box-container .h5 > a,.box-container .h6 > a',

      'third_bg' => "body",
      'third_color' => ""
    );
  }


?>