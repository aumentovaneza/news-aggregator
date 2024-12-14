<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NYTimesService
{
    protected $baseUrl;
    protected $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.nytimes.api_url', env('NYT_API_URL'));
        $this->apiKey = config('services.nytimes.api_key', env('NYT_API_KEY'));
    }

    public function fetchArticles(string $category = 'home')
    {
        $response = Http::get("{$this->baseUrl}/{$category}.json", [
            'api-key' => $this->apiKey,
        ]);
        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Error fetching articles: ' . $response->body());
    }
}
