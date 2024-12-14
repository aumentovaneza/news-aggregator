<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GuardianService
{
    protected $baseUrl;
    protected $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.guardian.api_url', env('GUARDIAN_API_URL'));
        $this->apiKey = config('services.guardian.api_key', env('GUARDIAN_API_KEY'));
    }

    public function fetchArticles(string $query, int $pageSize = 10)
    {
        $response = Http::get("{$this->baseUrl}/search", [
            'q'         => $query,
            'page-size' => $pageSize,
            'api-key'   => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $response->json()['response']['results'];
        }

        throw new \Exception('Error fetching articles: ' . $response->body());
    }
}
