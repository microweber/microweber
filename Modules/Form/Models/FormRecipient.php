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
    use FiresModelLifecycleEvents;

    use Notifiable;

    public $table = 'forms_recipients';
}
