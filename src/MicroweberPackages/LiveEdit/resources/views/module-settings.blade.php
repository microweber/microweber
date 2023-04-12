@extends('admin::layouts.base')



@section('content')

    <div>


        <?php
        $moduleTypeForComponent = str_replace('/', '.', $moduleType);
        $hasError = false;
        $output = false;

//        try {
//            $output = \Livewire\Livewire::mount('live-edit::' . $moduleTypeForComponent, [
//                //'id' => $moduleId,
//                'moduleId' => $moduleId,
//                'moduleType' => $moduleType,
//            ])->html();
//
//        } catch (\Livewire\Exceptions\ComponentNotFoundException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        } catch (InvalidArgumentException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        } catch (\Exception $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        }
//
//        if ($hasError) {
//            print '<div class="alert alert-danger" role="alert">';
//            print $output;
//            print '</div>';
//        } else {
//            print $output;
//        }


        ?>
        @livewire('live-edit::' . $moduleTypeForComponent, [
            'moduleId' => $moduleId,
            'moduleType' => $moduleType,
        ])

    </div>







    <script>

        Livewire.on('settingsChanged', $data => {
            if (typeof mw !== 'undefined' && mw.top().app && mw.top().app.editor) {
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ($data || {}))
            }
        })
    </script>

@endsection
