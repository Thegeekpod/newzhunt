<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lead story
        $leadArticle = Article::published()
            ->where('is_lead', true)
            ->latest('published_at')
            ->with(['category', 'author'])
            ->first();

        // Sub leads
        $subLeads = Article::published()
            ->where('is_sub_lead', true)
            ->latest('published_at')
            ->with(['category', 'author'])
            ->take(2)
            ->get();

        // Latest news (main list)
        $latestArticles = Article::published()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Politics articles
        $politicsArticles = Article::published()
            ->whereHas('category', function ($query) {
                $query->where('slug', 'politics');
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Sports articles
        $sportsArticles = Article::published()
            ->whereHas('category', function ($query) {
                $query->where('slug', 'sports');
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // International articles
        $internationalArticles = Article::published()
            ->whereHas('category', function ($query) {
                $query->where('slug', 'international');
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Entertainment articles
        $entertainmentArticles = Article::published()
            ->whereHas('category', function ($query) {
                $query->where('slug', 'entertainment');
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Videos
        $videos = Video::latest()->take(3)->get();

        // Special banner article
        $specialArticle = Article::published()
            ->where('is_special_banner', true)
            ->latest('published_at')
            ->first();

        return view('home', compact(
            'leadArticle',
            'subLeads',
            'latestArticles',
            'politicsArticles',
            'sportsArticles',
            'internationalArticles',
            'entertainmentArticles',
            'videos',
            'specialArticle'
        ));
    }

    public function videos()
    {
        $videos = Video::latest()->paginate(12);
        return view('videos', compact('videos'));
    }

    public function topStories()
    {
        $articles = Article::published()
            ->where(function ($query) {
                $query->where('is_lead', true)
                      ->orWhere('is_sub_lead', true);
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->paginate(10);
        return view('top-news', compact('articles'));
    }

    /**
     * Background weather refresh — called via AJAX after page load.
     * Returns current DB weather data immediately. If cache expired and
     * auto-fetch is on, triggers a fresh API fetch before responding.
     */
    public function refreshWeather()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        if (($settings['weather_auto_fetch'] ?? '0') === '1' && !empty($settings['weather_api_key'])) {
            if (!\Illuminate\Support\Facades\Cache::has('weather_last_fetched')) {
                \App\Http\Controllers\Admin\SettingsController::fetchWeather(
                    $settings['weather_api_key'],
                    $settings['weather_location'] ?? 'Durgapur'
                );
                // Reload settings after fetch
                $settings = \App\Models\Setting::all()->pluck('value', 'key');
            }
        }

        return response()->json([
            'temp'     => $settings['weather_temp'] ?? '--',
            'desc'     => $settings['weather_desc'] ?? '',
            'humidity' => $settings['weather_humidity'] ?? '--',
            'wind'     => $settings['weather_wind'] ?? '--',
            'high'     => $settings['weather_high'] ?? '--',
            'low'      => $settings['weather_low'] ?? '--',
            'location' => $settings['weather_location'] ?? '',
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $articles = Article::published()
            ->when($query, function ($q, $query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                });
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->paginate(10);

        return view('search', compact('articles', 'query'));
    }
}
