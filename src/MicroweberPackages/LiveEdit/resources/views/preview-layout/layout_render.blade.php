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

    <div id="preview-layout-file">
        <module id="preview-layout-<?php echo md5($layoutFile); ?>-layout" type="layouts" template="<?php echo $layoutFile; ?>" />
    </div>


@endsection
