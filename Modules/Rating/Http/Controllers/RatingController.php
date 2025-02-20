<?php

namespace Modules\Rating\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rating\Models\Rating;

class RatingController extends Controller
{
    public function save(Request $request)
    {
        if (!$request->has('rel_id') || !$request->has('rel_type')) {
            return response()->json(['error' => 'Missing required parameters'], 422);
        }

        $data = [
            'rel_id' => $request->input('rel_id'),
            'rel_type' => $request->input('rel_type'),
            'rating' => $request->input('rating', 0),
            'session_id' => session()->getId(),
        ];

        if ($request->has('comment')) {
            $data['comment'] = $request->input('comment');
        }

        // Check for existing rating
        $query = Rating::query()
            ->where('rel_id', $data['rel_id'])
            ->where('rel_type', $data['rel_type']);

        if (auth()->check()) {
            $query->where('created_by', auth()->id());
        } else {
            $query->where('session_id', $data['session_id']);
        }

        $existingRating = $query->first();

        // If rating is 0, delete the existing rating
        if ($data['rating'] == 0 && $existingRating) {
            $existingRating->delete();
            return response()->json(['success' => true]);
        }

        // Update or create rating
        if ($existingRating) {
            $existingRating->update($data);
            $rating = $existingRating;
        } else {
            $rating = Rating::create($data);
        }

        // Clear cache
        $cacheKey = md5($data['rel_type'] . $data['rel_id'] . 'avg');
        cache()->tags('rating')->forget($cacheKey);

        return response()->json([
            'success' => true,
            'rating' => $rating
        ]);
    }
}
