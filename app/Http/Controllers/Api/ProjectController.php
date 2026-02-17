<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();

        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($projects),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['in_progress', 'completed', 'delayed', 'draft'])],
            'end_at' => ['nullable', 'date', 'after_or_equal:now'],
            'basic_package_text' => ['nullable', 'string'],
        ]);

        // ✅ رفع الصورة (مفيش حذف صورة قديمة هنا لأن المشروع لسه جديد)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create($data);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
        ], 201);
    }

    public function show(Project $project)
    {
        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['sometimes', 'string', 'max:255'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', Rule::in(['in_progress', 'completed', 'delayed', 'draft'])],
            'end_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:now'],
            'basic_package_text' => ['sometimes', 'nullable', 'string'],
        ]);

        // ✅ لو في صورة جديدة: احذف القديمة وبعدين خزّن الجديدة
        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project->fresh()),
        ]);
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
