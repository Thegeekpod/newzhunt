<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer(['layouts.app', 'home', 'category', 'article', 'coming-soon', 'search', 'videos', 'top-news'], function ($view) {
            $popular = \App\Models\Article::published()->where('is_popular', true)->orderBy('view_count', 'desc')->take(5)->get();
            if ($popular->isEmpty()) {
                $popular = \App\Models\Article::published()->orderBy('view_count', 'desc')->take(5)->get();
            }

            $latest = \App\Models\Article::published()->where('is_latest', true)->orderBy('created_at', 'desc')->take(5)->get();
            if ($latest->isEmpty()) {
                $latest = \App\Models\Article::published()->orderBy('created_at', 'desc')->take(5)->get();
            }

            // Always serve instantly from database — weather is refreshed via background AJAX
            $settings = \App\Models\Setting::all()->keyBy('key')->map(fn($item) => $item->value);

            $view->with([
                'g_categories' => \App\Models\Category::orderBy('display_order', 'asc')->get(),
                'g_tickers' => \App\Models\Ticker::where('is_active', true)->get(),
                'g_popular' => $popular,
                'g_latest' => $latest,
                'g_poll' => \App\Models\Poll::where('is_active', true)->with('options')->first(),
                'g_settings' => $settings,
                'g_ads' => \App\Models\Advertisement::where('is_active', true)->get()->groupBy('slot_name')
            ]);
        });
    }
}
