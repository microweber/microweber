<?php
$post = get_content_by_id(CONTENT_ID);
$picture = get_picture(CONTENT_ID);

if (!$picture) {
    $picture = '';
}

$itemData = content_data(CONTENT_ID);
$itemTags = content_tags(CONTENT_ID);
?>
@extends('bootstrap::layouts.master')

@section('content')
    <div class="blog-inner-page py-5" id="blog-content-<?php print CONTENT_ID; ?>">
        <div class="container mw-m-t-30 mw-m-b-50">
            <div class="row">

                <?php if ($picture != '' AND $picture != false): ?>

                <div class="background-image-holder h-650" style="background-image: url('<?php print $picture; ?>');">

                </div>
                <?php endif; ?>

                <h2 class="mt-5 text-center  text-dark"><?php echo $post['title']; ?></h2>
                <p class="text-dark text-center"><?php echo date('d M Y', strtotime($post['created_at'])); ?></p>

                <div class="col-md-10 col-12 mx-auto">

                    <div class="description edit dropcap typography-area" field="content" rel="content">

                    </div>
                    <module type="sharer" id="post-bottom-sharer" class="py-3 float-start"
                            style="xwidth: calc(100% - 45px);"/>
                </div>

            </div>
        </div>
    </div>
@endsection
