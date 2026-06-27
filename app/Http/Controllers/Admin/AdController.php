<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdController extends Controller
{
    public function index()
    {
        $ads = Advertisement::all();
        return view('admin.ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slot_name' => 'required|string',
            'destination_url' => 'nullable|url',
            'image' => 'required|file',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'ad_' . $request->slot_name . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ads'), $filename);
            $imagePath = 'uploads/ads/' . $filename;
        }

        Advertisement::create([
            'slot_name' => $request->slot_name,
            'destination_url' => $request->destination_url,
            'image_url' => $imagePath,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement created successfully.');
    }

    public function update(Request $request, Advertisement $ad)
    {
        $request->validate([
            'slot_name' => 'required|string',
            'destination_url' => 'nullable|url',
            'image' => 'nullable|file',
        ]);

        $updateData = [
            'slot_name' => $request->slot_name,
            'destination_url' => $request->destination_url,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('image')) {
            // Remove old image file if exists and is not a default asset
            if ($ad->image_url && !str_contains($ad->image_url, 'assets/')) {
                $oldPath = str_starts_with($ad->image_url, 'http') 
                    ? str_replace(asset(''), public_path(''), $ad->image_url) 
                    : public_path($ad->image_url);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('image');
            $filename = 'ad_' . $request->slot_name . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ads'), $filename);
            $updateData['image_url'] = 'uploads/ads/' . $filename;
        }

        $ad->update($updateData);

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement updated successfully.');
    }

    public function upload(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);

        $request->validate([
            'image' => 'required|file',
        ]);

        if ($request->hasFile('image')) {
            // Remove old image file if exists and is not a default asset
            if ($ad->image_url && !str_contains($ad->image_url, 'assets/')) {
                $oldPath = str_starts_with($ad->image_url, 'http') 
                    ? str_replace(asset(''), public_path(''), $ad->image_url) 
                    : public_path($ad->image_url);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('image');
            $filename = 'ad_' . $ad->slot_name . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ads'), $filename);
            
            $ad->update([
                'image_url' => 'uploads/ads/' . $filename,
                'is_active' => true // Activate slot automatically on upload
            ]);
        }

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement banner uploaded successfully.');
    }

    public function destroy(Advertisement $ad)
    {
        // Delete image file if exists and is not a default asset
        if ($ad->image_url && !str_contains($ad->image_url, 'assets/')) {
            $oldPath = str_starts_with($ad->image_url, 'http') 
                ? str_replace(asset(''), public_path(''), $ad->image_url) 
                : public_path($ad->image_url);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }
        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement deleted successfully.');
    }
}

