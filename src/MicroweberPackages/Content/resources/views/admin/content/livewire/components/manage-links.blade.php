<div class="manage-post-item-links mt-3">

    <a href="{{$content->editLink()}}" class="btn btn-outline-primary btn-sm">Edit</a>
    <a href="{{$content->editLink()}}" class="btn btn-outline-success btn-sm">Live Edit</a>

    <?php if(!$content->is_deleted): ?>
    <a href="javascript:mw.admin.content.delete('{{ $content->id }}');" class="btn btn-outline-danger btn-sm js-delete-content-btn-{{ $content->id }}">Delete</a>
    <?php endif; ?>
    @if ($content->is_active < 1)
        <a href="javascript:mw.admin.content.publishContent('{{ $content->id }}');" class="mw-set-content-unpublish badge badge-warning font-weight-normal">Unpublished</a>

    @endif
</div>
