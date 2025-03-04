<?php

namespace Modules\Newsletter\Http;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Newsletter\Models\NewsletterSubscriber;

class NewsletterController extends Controller
{
    /**
     * Subscribe a user to the newsletter.
     *
     * Expected POST parameters:
     * - name: the subscriber's name
     * - email: the subscriber's email address
     * - list_id: the newsletter list identifier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $data = $request->only(['name', 'email', 'list_id']);

        $validator = Validator::make($data, [
            'name'    => 'required|string',
            'email'   => 'required|email',
         //   'list_id' => 'required|integer'
        ]);

        $list_id = $data['list_id'] ?? 0;

        if ($validator->fails()) {
            return response()->json([
                'success'  => false,
                'errors'   => $validator->errors()
            ], 422);
        }

        // Create or update subscriber.
        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                'name'          => $data['name'],
               // 'list_id'       => $data['list_id'],
                'is_subscribed' => 1,
            ]
        );

        if($list_id){
            //attach to list

        }


        return response()->json([
            'success'    => true,
            'subscriber' => $subscriber
        ]);
    }
}
