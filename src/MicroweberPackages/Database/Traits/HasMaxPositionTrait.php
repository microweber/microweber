<?php

namespace MicroweberPackages\Database\Traits;

trait  HasMaxPositionTrait {

    public function updateMaxPositionFieldOnModel()
    {
        $maxPosition = 0;

        if(!isset($this->position) && isset($this->rel_id) && isset($this->rel_type)) {

            $position = get_class($this)::where([
                ['rel_id', '=', $this->rel_id],
                ['rel_type', '=', $this->rel_type]
            ])->max('position');

            $maxPosition = $position + 1;
        }

        $this->position = $maxPosition;

        $this->save();

        return $this;
    }
}
