<?php

namespace App\Providers;

use App\Services\TravelMapService;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
Use App\Models\User;
Use App\Models\Country;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TravelMapService::class, function ($app) {
        return new TravelMapService();
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $suggestedTravellers = User::whereHas('profile', function($q) {
                    $q->where('role', 'traveller');
                })
                ->with('profile')
                ->withCount('followers')
                ->orderByDesc('followers_count')
                ->when(auth()->check(), function($q) {
                    $q->where('id', '!=', auth()->id());
                })
                ->take(5)
                ->get();

            $trendingCountries = Country::withCount('posts')
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->take(5)
                ->get();

            $view->with('suggestedTravellers', $suggestedTravellers)
                 ->with('trendingCountries', $trendingCountries);
        });
    }
}
