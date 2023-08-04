<div>

    <?php
    $content_id = false;
    if (isset($params['content-id'])) {
        $content_id = $params['content-id'];
    }
    ?>

    <livewire:custom-fields-list content_id="{{$content_id}}" content_type="product" />
</div>
