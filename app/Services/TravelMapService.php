<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class TravelMapService
{
    /**
     * Get the list of 2-letter ISO codes (e.g., 'US', 'FR') for the map.
     * Includes both TAGGED posts and HOME BASE.
     */
    public function getVisitedCountryCodes(User $user)
    {
        $dbCodes = $user->posts()
            ->join('countries', 'posts.country_id', '=', 'countries.id')
            ->distinct()
            ->pluck('countries.iso_code')
            ->toArray();

        if ($user->profile && $user->profile->country) {
            $homeCode = $user->profile->country->iso_code;
            $dbCodes[] = $homeCode;
        }

        $dbCodes = array_unique($dbCodes);

        if (empty($dbCodes)) {
            return [];
        }

        $cacheKey = 'map_data_v4_' . md5(json_encode($dbCodes));

        return Cache::remember($cacheKey, 86400, function () use ($dbCodes) {
            $verifiedCodes = [];

            try {
                $response = Http::withoutVerifying()->get('https://restcountries.com/v3.1/alpha', [
                    'codes' => implode(',', $dbCodes)
                ]);

                if ($response->successful()) {
                    foreach ($response->json() as $countryData) {
                        if (isset($countryData['cca2'])) {
                            $verifiedCodes[] = $countryData['cca2'];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Map API Error: " . $e->getMessage());
            }
            
            return empty($verifiedCodes) ? $dbCodes : $verifiedCodes;
        });
    }
}