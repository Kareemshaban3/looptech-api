<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->get();

        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviews),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stars' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('reviews', 'public');
        }

        $review = Review::create($data);

        return response()->json([
            'success' => true,
            'data' => new ReviewResource($review),
        ], 201);
    }

    public function show(Review $review)
    {
        return response()->json([
            'success' => true,
            'data' => new ReviewResource($review),
        ]);
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'stars' => ['sometimes', 'integer', 'min:1', 'max:5'],
        ]);

        if ($request->hasFile('image')) {
            if ($review->image) {
                Storage::disk('public')->delete($review->image);
            }
            $data['image'] = $request->file('image')->store('reviews', 'public');
        }

        $review->update($data);

        return response()->json([
            'success' => true,
            'data' => new ReviewResource($review->fresh()),
        ]);
    }

    public function destroy(Review $review)
    {
        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }

        $review->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
