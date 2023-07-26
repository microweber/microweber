@if($item and isset($item['id']) and isset($item['data-type']))

    @php

        $moduleInfo = module_info($item['data-type']);

    @endphp

    @if($moduleInfo && isset($moduleInfo['name']))
        <div id="sidebar-admin-modules-list-tree-item-{{ $item['id'] }}">
            <span class="cursor-pointer" onclick="window.scrollToModule('{{ $item['id'] }}')">
                <?php if (isset($moduleInfo['icon'])) { ?>
            <img src="<?php print $moduleInfo['icon']; ?>"
                 style="max-width: 20px; max-height: 20px; margin-right: 10px;"/>
            <?php } ?>

                @lang($moduleInfo['name'])
                </span>
            <span>

            <button onclick="window.selectModule('{{ $item['id'] }}')"> @lang('Settings')</button>
            </span>
        </div>

    @endif
@endif

