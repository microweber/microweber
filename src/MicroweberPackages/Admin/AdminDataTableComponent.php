<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Admin;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

abstract class AdminDataTableComponent extends DataTableComponent
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->setupColumnSelect();
        $this->setupPagination();
        $this->setupSecondaryHeader();
        $this->setupFooter();
        $this->setupReordering();

        return view('livewire::livewire.livewire-tables.datatable')
            ->with([
                'columns' => $this->getColumns(),
                'rows' => $this->getRows(),
                'customView' => $this->customView(),
            ]);
    }
}
