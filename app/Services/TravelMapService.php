<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class TravelMapService
{
    /**
     * Get the list of 2-letter ISO codes (e.g., 'US', 'FR') for the map.
     * Uses External API (RestCountries) to fetch and validate data.
     */
    public function getVisitedCountryCodes(User $user)
    {
        $dbCodes = $user->posts()
            ->join('countries', 'posts.country_id', '=', 'countries.id')
            ->distinct()
            ->pluck('countries.iso_code')
            ->toArray();

        if (empty($dbCodes)) {
            return [];
        }

        $cacheKey = 'visited_countries_v1_' . md5(json_encode($dbCodes));

        return Cache::remember($cacheKey, 86400, function () use ($dbCodes) {
            $verifiedCodes = [];

            $response = Http::get('https://restcountries.com/v3.1/alpha', [
                'codes' => implode(',', $dbCodes)
            ]);

            if ($response->successful()) {
                foreach ($response->json() as $countryData) {
                    if (isset($countryData['cca2'])) {
                        $verifiedCodes[] = $countryData['cca2'];
                    }
                }
            }

            return empty($verifiedCodes) ? $dbCodes : $verifiedCodes;
        });
    }
}