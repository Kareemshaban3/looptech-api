<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkResource;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkController extends Controller
{
    public function index()
    {
        $works = Work::latest()->get();

        return response()->json([
            'success' => true,
            'data' => WorkResource::collection($works),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'text' => ['required', 'string'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('works', 'public');
        }

        $work = Work::create($data);

        return response()->json([
            'success' => true,
            'data' => new WorkResource($work),
        ], 201);
    }

    public function show(Work $work)
    {
        return response()->json([
            'success' => true,
            'data' => new WorkResource($work),
        ]);
    }

    public function update(Request $request, Work $work)
    {
        $data = $request->validate([
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'text' => ['sometimes', 'string'],
        ]);

        if ($request->hasFile('image')) {
            if ($work->image) {
                Storage::disk('public')->delete($work->image);
            }
            $data['image'] = $request->file('image')->store('works', 'public');
        }

        $work->update($data);

        return response()->json([
            'success' => true,
            'data' => new WorkResource($work->fresh()),
        ]);
    }

    public function destroy(Work $work)
    {
        if ($work->image) {
            Storage::disk('public')->delete($work->image);
        }

        $work->delete();

        return response()->json([
            'success' => true,
        ]);
    }


    
}
