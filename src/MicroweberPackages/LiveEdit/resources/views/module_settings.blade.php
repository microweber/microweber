<?php
dump($moduleId);
dump($moduleType);

?>

<?php print \Livewire\Livewire::mount('live-edit::btn', [
    'id' => $moduleId,
    'type' => $moduleType,
])->html();  ?>
