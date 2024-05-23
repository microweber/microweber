@if ($persistIsEnabled)
<div x-data="{
  layoutView: $persist(@entangle('layoutView').live).as('_x_{{ $persistToggleStatusName }}'),
}"></div>
@endif
