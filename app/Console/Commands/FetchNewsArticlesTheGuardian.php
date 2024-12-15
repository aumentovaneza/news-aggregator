<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use App\Services\GuardianService;

class FetchNewsArticlesTheGuardian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles-the-guardian {query?} {--limit=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update articles from The Guardian';

    protected $guardianService;

    public function __construct(GuardianService $guardianService)
    {
        parent::__construct();
        $this->guardianService = $guardianService;
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = $this->argument('query') ?? '';
        $limit = (int) $this->option('limit');
        $source = 'TheGuardian';

        try {

            $articles = $this->guardianService->fetchArticles($query, $limit);
            if (empty($articles)) {

                $this->info("No news articles found from $source.");
                return Command::SUCCESS;
            }

            $existingTitles = Article::all()->keyBy('title')->toArray();

            foreach ($articles as $article) {
                if (in_array($article['webTitle'], $existingTitles)) {
                    continue;
                }

                Article::create([
                    'source' => 'The Guardian',
                    'title' => $article['webTitle'],
                    'date_published' => $article['webPublicationDate'],
                    'category' => $article['sectionName'],
                    'api_source' => $source,
                    'details' => json_decode(json_encode([
                        'id' => $article['id'],
                        'type' => $article['type'],
                        'webUrl' => $article['webUrl'],
                        'apiUrl' => $article['apiUrl'],
                        'pillarName' => $article['pillarName'],
                    ]), true)
                ]);
            }

            $this->info("Articles fetched successfully from $source.");
        } catch (\Exception $e) {
            $this->error("Unable to fetch articles: " . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
