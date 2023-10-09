<div>

    <?php
    $relType = 'content';
    $relId = 0;
    if (isset($params['content-id'])) {
        $relId = $params['content-id'];
    }
    if (isset($params['for'])) {
        $relType = $params['for'];
    }
    if (isset($params['for-id'])) {
        $relId = $params['for-id'];
    }
    ?>

    <livewire:custom-fields-list relId="{{$relId}}" relType="{{$relType}}" />
</div>
