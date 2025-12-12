<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CountryIntelService
{
    public function getCountryProfile(string $countryName, ?string $isoCode = null): array
    {
        // 1. Cache Key (Updated to v10 to force fresh data)
        $cacheKey = "country_intel_v10_" . strtolower(str_replace(' ', '_', $countryName));

        // 2. Return Cache
        return Cache::remember($cacheKey, 43200, function () use ($countryName, $isoCode) {
            
            // 3. Default Data (Matches your UI requirements)
            $data = [
                'capital' => 'Unknown',
                'population' => 'Unknown',
                'currency' => null, 
                'summary' => $this->getFallbackSummary($countryName), // Start with fallback
            ];

            // --- PART A: REST COUNTRIES (Stats) ---
            try {
                $endpoint = $isoCode ? "alpha/{$isoCode}" : "name/" . urlencode($countryName);
                
                // 'withoutVerifying' fixes local SSL issues
                $response = Http::withoutVerifying()->timeout(3)->get("https://restcountries.com/v3.1/{$endpoint}");

                if ($response->successful()) {
                    $countryData = $response->json()[0] ?? [];
                    
                    // Capital
                    $data['capital'] = $countryData['capital'][0] ?? 'N/A';
                    
                    // Population (Formatted)
                    if (isset($countryData['population'])) {
                        $val = $countryData['population'];
                        $data['population'] = ($val >= 1000000) ? number_format($val / 1000000, 1) . ' M' : number_format($val);
                    }

                    // Currency
                    if (isset($countryData['currencies'])) {
                        $code = array_key_first($countryData['currencies']);
                        $data['currency'] = ['code' => $code, 'rate' => '1.00'];
                        
                        // Try Real Exchange Rate
                        try {
                            $rateRes = Http::withoutVerifying()->timeout(2)->get("https://api.frankfurter.app/latest?from=USD&to={$code}");
                            if ($rateRes->successful()) {
                                $data['currency']['rate'] = $rateRes->json()['rates'][$code] ?? 1.00;
                            }
                        } catch (\Exception $e) {}
                    }
                }
            } catch (\Exception $e) {
                Log::error("RestCountries Error: " . $e->getMessage());
            }

            // --- PART B: GEMINI AI (Using Gemini 2.5 Flash) ---
            $apiKey = config('services.gemini.key');

            if ($apiKey) {
                try {
                    $prompt = "Write a captivating, 2-sentence travel summary for {$countryName}. Focus on the vibe. Plain text only.";

                    // Using the model confirmed in your list: gemini-2.5-flash
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

                    $aiResponse = Http::withoutVerifying()
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, [
                            'contents' => [['parts' => [['text' => $prompt]]]]
                        ]);

                    if ($aiResponse->successful()) {
                        $text = $aiResponse->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                        if (!empty($text)) {
                            $data['summary'] = trim($text); // Success! Overwrite fallback.
                        }
                    } else {
                        Log::error("Gemini API Error: " . $aiResponse->body());
                    }
                } catch (\Exception $e) {
                    Log::error("Gemini Connection Error: " . $e->getMessage());
                }
            }

            return $data;
        });
    }

    /**
     * Robust fallback summaries so the UI always looks professional.
     */
    private function getFallbackSummary(string $name): string
    {
        $fallbacks = [
            'Japan' => "Japan is a captivating blend of ancient traditions and futuristic innovation, offering everything from serene temples to neon-lit skylines.",
            'France' => "France entices visitors with its world-class cuisine, iconic landmarks like the Eiffel Tower, and the charming vineyards of its countryside.",
            'United States' => "The United States offers a diverse tapestry of landscapes, from the bustling streets of New York City to the natural wonders of the Grand Canyon.",
            'United Kingdom' => "The United Kingdom is rich in history and culture, featuring historic castles, vibrant cities, and the rolling green hills of the countryside.",
            'Italy' => "Italy captures the heart with its Roman history, Renaissance art, and exquisite culinary traditions set against beautiful Mediterranean scenery.",
            'Spain' => "Spain offers a vibrant mix of sunny beaches, historic architecture, and a lively culture famous for its flamenco music and delicious tapas.",
            'Australia' => "Australia beckons with its stunning coastline, unique wildlife, and the rugged beauty of the Outback, perfect for adventure seekers.",
        ];

        if (isset($fallbacks[$name])) {
            return $fallbacks[$name];
        }

        return "{$name} is a unique destination waiting to be explored, offering a rich cultural heritage and unforgettable travel experiences.";
    }
}