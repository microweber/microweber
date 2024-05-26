<?php

Route::name('admin.livewire.components.')
    ->prefix('admin-livewire-components')
    ->middleware(['admin'])
    ->group(function () {

        \Illuminate\Support\Facades\Route::get('render-component', function () {

            $params = request()->all();

            $componentName = '';
            $componentAttributes = [];

            if (isset($params['componentName'])) {
                $componentName = $params['componentName'];
                unset($params['componentName']);
            }
            foreach ($params as $key => $value) {
                $componentAttributes[$key] = $value;
            }

            return view('livewire::livewire.render-component', [
                'componentName' => $componentName,
                'componentAttributes' => $componentAttributes,
                'livewireId'=>uniqid()
            ]);

        })->name('render-component');

    });
