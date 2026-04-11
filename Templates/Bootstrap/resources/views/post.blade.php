<?php
$post = get_content_by_id(CONTENT_ID);
$picture = get_picture(CONTENT_ID);

if (!$picture) {
    $picture = '';
}

$itemData = content_data(CONTENT_ID);
$itemTags = content_tags(CONTENT_ID);
?>
@extends('templates.bootstrap::layouts.master')

@section('content')
    <div class="blog-inner-page py-5" id="blog-content-<?php print CONTENT_ID; ?>">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-12">

                    <?php if ($picture != '' AND $picture != false): ?>
                    <div class="mb-4 rounded overflow-hidden">
                        <img src="<?php print $picture; ?>" alt="<?php echo e($post['title']); ?>" class="w-100" style="max-height: 500px; object-fit: cover;">
                    </div>
                    <?php endif; ?>

                    <h1 class="mt-4 mb-2 text-center"><?php echo $post['title']; ?></h1>
                    <p class="text-muted text-center mb-5"><?php echo date('d M Y', strtotime($post['created_at'])); ?></p>

                    <div class="description edit dropcap typography-area" field="content" rel="content">

                    </div>

                    <hr class="my-4">

                    <module type="sharer" id="post-bottom-sharer" class="py-3"/>
                </div>
            </div>
        </div>
    </div>
@endsection
