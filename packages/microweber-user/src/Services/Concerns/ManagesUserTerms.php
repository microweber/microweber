<?php

namespace MicroweberPackages\User\Services\Concerns;

use MicroweberPackages\User\Services\TosManager;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ManagesUserTerms
{
    public function terms_accept($tos_name, $user_id_or_email = false)
    {
        $tos = new TosManager();
        return $tos->terms_accept($tos_name, $user_id_or_email);

    }

    public function terms_check($tos_name = false, $user_id_or_email = false)
    {

        $tos = new TosManager();
        return $tos->terms_check($tos_name, $user_id_or_email);

    }
}
