<div>

  @livewire('admin-list-custom-fields', [
    'relId' => $this->data['id'],
    'relType' => morph_name($this->getModel()),
])
</div>
