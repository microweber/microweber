<?php

/*

type: layout
content_type: static
name: Clean
position: 6
description: Clean

*/
$extends = 'templates.bootstrap::layouts.master';

if(isset($templateViewsName) and !empty($templateViewsName)) {
    $extends = 'templates.' . $templateViewsName . '::layouts.master';
}

?>
@extends($extends)

@section('content')

    <div id="preview-skin-file">
        <module id="module-preview-<?php echo md5($skin); ?>-module" type="<?php echo $module; ?>" template="<?php echo $skin; ?>" />
    </div>


@endsection
