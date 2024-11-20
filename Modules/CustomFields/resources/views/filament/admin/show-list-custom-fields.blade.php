<div>

    @php
    $relId = 0;
    if (isset($this->data['id'])) {
        $relId = $this->data['id'];
    }
    @endphp

  @livewire('admin-list-custom-fields', [
    'relId' => $relId,
    'relType' => morph_name($this->getModel()),
])
</div>
