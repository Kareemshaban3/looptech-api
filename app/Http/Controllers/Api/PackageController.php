<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('features')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => PackageResource::collection($packages),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'feature' => ['nullable', 'string', 'max:255'],
        ]);

        $package = DB::transaction(function () use ($data) {
            $package = Package::create([
                'name' => $data['name'],
                'price' => $data['price'],
            ]);

            if (!empty($data['feature'])) {
                $package->features()->create([
                    'text' => $data['feature'],
                ]);
            }

            return $package->load('features');
        });

        return response()->json([
            'success' => true,
            'data' => new PackageResource($package),
        ], 201);
    }

    public function show(Package $package)
    {
        return response()->json([
            'success' => true,
            'data' => new PackageResource($package->load('features')),
        ]);
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'feature' => ['sometimes', 'nullable', 'string', 'max:255'], // feature واحدة فقط
        ]);

        DB::transaction(function () use ($package, $data) {
            $package->update([
                'name' => $data['name'] ?? $package->name,
                'price' => $data['price'] ?? $package->price,
            ]);

            if (array_key_exists('feature', $data)) {
                $package->features()->delete(); 
                if (!empty($data['feature'])) {
                    $package->features()->create(['text' => $data['feature']]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'data' => new PackageResource($package->fresh()->load('features')),
        ]);
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function addFeature(Request $request, Package $package)
{
    $data = $request->validate([
        'feature' => ['required', 'string', 'max:255'],
    ]);

    $package->features()->create([
        'text' => $data['feature']
    ]);

    return response()->json([
        'success' => true,
        'data' => new PackageResource($package->fresh()->load('features')),
    ]);
}

}
