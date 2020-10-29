<?php


namespace MicroweberPackages\Queue\Http\Controllers;

use \Illuminate\Routing\Controller;
use MicroweberPackages\Queue\Jobs;

class ProcessQueueController extends Controller
{
    public function handle()
    {
        $limit = 10;
        $allQueue = Jobs::whereNull('reserved')->where(function($query)
        {
            $query->where('attempts', '<', 10);
            $query->orWhereNull('attempts');
        })->limit($limit)->get();

        if ($allQueue) {
            //>>> Make reserved
            foreach($allQueue as $queue) {
                $queue->reserved = true;
                $queue->save();
            }
            //<<< Make reserved

            foreach ($allQueue as $queueItem) {
                $payload = $queueItem->payload;
                if ($payload) {
                    $payload = @json_decode($payload, true);
                    $command = @unserialize($payload['data']['command']);

                    if (is_object($command)) {
                        try {
                            dispatch_now($command);
                            $queueItem->delete();
                        } catch (\Exception $e) {
                            $queueItem->reserved = null;
                            $queueItem->attempts = (int)$queueItem->attempts + 1;

                            $queueItem->save();
                            \Log::error($e);
                        }
                    }
                }
            }
        }
    }
}