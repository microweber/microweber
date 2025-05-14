<?php

use Illuminate\Support\Facades\Route;
use Modules\Ai\Facades\Ai;

Route::middleware(['admin'])->group(function () {
    Route::post('api/ai/chat', function (Illuminate\Http\Request $request) {

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
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    });
});
