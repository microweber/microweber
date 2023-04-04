@extends('admin::layouts.base')

@section('title', 'Module Settings')


@section('content')

    <div>
        <script>

            Livewire.on('settingsChanged', $data => {
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ($data || {}))
             })
        </script>


        <?php
        dump($moduleId);
        dump($moduleType);

        ?>
        <?php
        $moduleTypeForComponent = str_replace('/', '.', $moduleType);
        $hasError = false;
        $output = false;

        try {
            $output = \Livewire\Livewire::mount('live-edit::' . $moduleTypeForComponent, [
                'id' => $moduleId,
                'type' => $moduleType,
            ])->html();

        } catch (\Livewire\Exceptions\ComponentNotFoundException $e) {
            $hasError = true;
            $output = $e->getMessage();
        }

        if ($hasError) {
            print '<div class="alert alert-danger" role="alert">';
            print $output;
            print '</div>';
        } else {
            print $output;
        }


        ?>
    </div>

@endsection





