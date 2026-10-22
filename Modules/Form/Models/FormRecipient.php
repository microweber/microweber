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

namespace Modules\Form\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class FormRecipient extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Form\Events\FormRecipientWasCreated::class,
        'updated' => \Modules\Form\Events\FormRecipientWasUpdated::class,
        'deleted' => \Modules\Form\Events\FormRecipientWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use Notifiable;

    public $table = 'forms_recipients';
}
