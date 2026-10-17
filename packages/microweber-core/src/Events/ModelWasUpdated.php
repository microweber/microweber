<?php

namespace MicroweberPackages\Core\Events;

/**
 * Generic model lifecycle event carrying the affected model. Fired by the
 * FiresModelLifecycleEvents trait for models without a dedicated event class.
 * Listen and filter by $event->getModel()::class.
 */
class ModelWasUpdated extends AbstractResourceWasUpdated
{
}
