<?php
Route::get('bb', function () {

    //$mod =  '<module type="shop/products" filter="contentData[applicable]=Bulgaria,United States" />';
    $mod =  '<module type="shop/products" filter="contentData[applicable][0]=Bulgaria&contentData[applicable][1]=" />';
   // $mod =  '<module type="shop/products" filter="contentData[applicable]=Bulgaria" />';
 return   app()->parser->process($mod);
});

Route::get('aaaaa', function () {
//    dump(3434);

    $params = [];
    //$params['filter']['contentData']['label'] = 'guz';
    //$params['filter']['contentData']['applicable'] = '';
    $params['filter']['contentData']['applicable'] = [
       '', 'Kuwait'
    ];
    //$params['filter']['priceBetween']  = '66,68';
    $params['no_cache']= true;
    $params['no_limit']= true;
//    $params['ne_sa_wrld_wide']= function ($builder){
//        //dd($builder);
//
//    };
   // $params['filter']['contentData']['applicable'] = 'United Kingdom';
    dd(get_content($params));
});



Route::prefix(ADMIN_PREFIX)->middleware(['admin'])->namespace('\MicroweberPackages\ContentFilter\Http\Controllers')->group(function () {

    Route::resource('content/filter', 'ContentFilterAdminController');

});

