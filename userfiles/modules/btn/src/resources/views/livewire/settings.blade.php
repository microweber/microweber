<div>

    <ul class="nav nav-tabs">
        <li class="nav-item" wire:ignore>
            <a href="#home" class="nav-link active" data-bs-toggle="tab">Text</a>
        </li>
        <li class="nav-item" wire:ignore>
            <a href="#profile" class="nav-link" data-bs-toggle="tab">Align</a>
        </li>
        <li class="nav-item" wire:ignore>
            <a href="#messages" class="nav-link" data-bs-toggle="tab">Align2</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" wire:ignore.self id="home">
            <div><input type="text" wire:model.debounce.100ms="settings.text"/></div>
        </div>
        <div class="tab-pane fade" wire:ignore.self id="profile">
            <div>
                <select wire:model.debounce.100ms="settings.align">
                    <option value="none">none</option>
                    <option value="left">left</option>
                    <option value="right">right</option>
                    <option value="center">center</option>
                </select></div>


        </div>
        <div class="tab-pane fade" wire:ignore.self id="messages">
            <div>


                <div>
                    <div>


                        <?php

                        $moduleTypeForComponent = 'live-edit::module-select-template';
                        $hasError = false;
                        $output = false;

                        try {
                            $output = \Livewire\Livewire::mount($moduleTypeForComponent, [
                                'moduleId' => $moduleId,
                                'moduleType' => $moduleType,
                            ])->html();

                        } catch (\Livewire\Exceptions\ComponentNotFoundException $e) {
                            $hasError = true;
                            $output = $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
                        } catch (\Exception $e) {
                            $hasError = true;
                            $output = $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
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
                </div>


            </div>


            <div class="mw-btn-settings-align-controls mt-4">
                <div>


                    {{ $settings['align'] }}

                    <div class="btn-group" role="group" aria-label="Button Align">
                        <button wire:click="setAlign('none')" type="button"
                                class="btn btn-primary @if($settings['align'] == 'none'): active @endif">None
                        </button>
                        <button wire:click="setAlign('left')" type="button"
                                class="btn btn-primary @if($settings['align'] == 'left'): active @endif">Left
                        </button>
                        <button wire:click="setAlign('center')" type="button"
                                class="btn btn-primary @if($settings['align'] == 'center'): active @endif">Middle
                        </button>
                        <button wire:click="setAlign('right')" type="button"
                                class="btn btn-primary @if($settings['align'] == 'right'): active @endif">Right
                        </button>
                    </div>

                </div>



                <x-mw-ui::icon-picker  wire:model="settings.icon" :value="$settings['icon']" />


            </div>
        </div>
    </div>


</div>

