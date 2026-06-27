<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_bn' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'duration' => 'nullable|string|max:50',
            'thumbnail_url' => 'nullable|url',
        ]);

        // If no thumbnail provided, try to extract YouTube video ID and use its default thumbnail
        $thumbnailUrl = $request->thumbnail_url;
        if (!$thumbnailUrl) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $request->youtube_url, $match);
            $youtubeId = $match[1] ?? null;
            if ($youtubeId) {
                $thumbnailUrl = "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
            } else {
                $thumbnailUrl = 'https://picsum.photos/seed/video/500/300';
            }
        }

        Video::create([
            'title_bn' => $request->title_bn,
            'youtube_url' => $request->youtube_url,
            'duration' => $request->duration ?? '3:00',
            'thumbnail_url' => $thumbnailUrl,
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Video added successfully.');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }
}
