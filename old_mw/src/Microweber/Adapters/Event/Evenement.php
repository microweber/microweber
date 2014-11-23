<?php
namespace Microweber\Adapters\Event;
use Evenement\EventEmitter;
class Evenement
{

    public $emitter;

    function __construct()
    {
        $emitter = new EventEmitter();
        $this->emitter = $emitter;
    }

    public function on($event_name, $callback)
    {
        return $this->emitter->on($event_name, $callback);
    }

    public function emit($event_name, $data)
    {
        return $this->emitter->emit($event_name, $data);

    }
}