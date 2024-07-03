<div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
    <input x-model="state"/>




    @livewire('admin-list-media-for-model', ['parentComponentName' => $this->getName(),  'relType' => $relType, 'relId' => $relId, 'sessionId' => $sessionId])



</div>
