<?php


namespace MicroweberPackages\Queue\Http\Controllers;

use \Illuminate\Routing\Controller;
use MicroweberPackages\Queue\Jobs;

class ProcessQueueController extends Controller
{
    public function handle()
    {
        $allQueue = Jobs::all();

        if ($allQueue) {
            $i = 1;
            foreach ($allQueue as $queueItem) {
                if($i >= 10) {
                    break;
                }
                $payload = $queueItem->payload;
                $queueItem->delete();
                $i++;
                if ($payload) {
                    $payload = @json_decode($payload, true);
                    $command = @unserialize($payload['data']['command']);

                    if (is_object($command)) {
                        dispatch_now($command);
                    }
                }
            }
        }
    }
}