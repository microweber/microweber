<div class="manage-post-item-links row p-0">

    <a  href="javascript:mw.admin.content.quickEditModalFrame('{{$content->editLink()}}')" class="btn btn-link btn-sm"><?php _e("Edit modal") ?></a>
    <a  href="{{$content->editLink()}}" class="btn btn-link btn-sm"><?php _e("Edit") ?></a>
    <a href="{{$content->editLink()}}" class="btn btn-link btn-sm"><?php _e("Live Edit") ?></a>

    <?php if(!$content->is_deleted): ?>
    <a href="javascript:mw.admin.content.delete('{{ $content->id }}');" class="btn btn-link btn-sm js-delete-content-btn-{{ $content->id }}"><?php _e("Delete") ?></a>
    <?php endif; ?>
    @if ($content->is_active < 1)
        <a href="javascript:mw.admin.content.publishContent('{{ $content->id }}');" class="mw-set-content-unpublish badge badge-warning font-weight-normal"><?php _e("Unpublished") ?></a>

    @endif
</div>

@if($isInTrashed)
    @include('content::admin.content.livewire.components.trash-buttons')
@endif
