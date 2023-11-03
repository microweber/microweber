<?php

namespace MicroweberPackages\SiteStats;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class DispatchServerSideTracking
{
    public $visitorId = '';
    public $session_id = '';

    public function setVisitorId($id)
    {
        $this->visitorId = $id;
    }

    public function setSessionId($id)
    {
        $this->session_id = $id;
    }

    public function dispatch()
    {

    }
}
