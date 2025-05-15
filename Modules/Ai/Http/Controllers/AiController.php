<?php

namespace Modules\Ai\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Ai\Facades\Ai;
use Modules\Ai\Facades\AiImages;

class AiController extends Controller
{
    public function editImage(Request $request)
    {
        // Validate the request
        $rules = [
            'prompt' => 'required|string',
            'url' => 'required|string',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->toArray()
            ], 422);
        }

        $prompt = $request->input('prompt');
        $imageUrl = $request->input('url');
        $options = $request->input('options', []);


        if ($imageUrl) {
            $options['image'] = $imageUrl;
        }

        try {
            // Process the image with AI
            $response = AiImages::generateImage($prompt, $options);

            $result = [
                'success' => true,
                'response' => $response,
                'data' => ''
            ];

            // Add the base64 data if available
            if (isset($response['data'])) {
                $result['data'] = $response['data'];
                unset($response['data']);
            }

            // Add the URL to the frontend response if available
            if (isset($response['url'])) {
                $result['url'] = $response['url'];
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function chat(Request $request)
    {
        $rules = [
            'messages' => 'required|array',
            'messages.*.role' => 'sometimes|string|in:system,user,assistant,function',
            'messages.*.content' => 'sometimes|string',
            'messages.*.name' => 'sometimes|string',
            'options' => 'sometimes|array',
            'options.functions' => 'sometimes|array',
            'options.function_call' => 'sometimes|string',
            'options.model' => 'sometimes|string',
            'options.temperature' => 'sometimes|numeric',
            'options.max_tokens' => 'sometimes|integer',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->toArray()
            ], 422);
        }


        $request->validate($rules);


        if (!$request->input('messages')) {
            return response()->json([
                'success' => false,
                'message' => 'Messages are required'
            ], 422);
        }

        $messages = $request->input('messages');
        $options = $request->input('options', []);


        $response = Ai::sendToChat($messages, $options);

        if (is_string($response)) {
            $response = @json_decode($response);
        }

        try {

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

