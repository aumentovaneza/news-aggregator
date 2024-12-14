<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use App\Services\NYTimesService;

class FetchNewsArticlesNYT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles-nyt {category?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update articles from The Guardian';

    protected $nytService;

    public function __construct(NYTimesService $nytService)
    {
        parent::__construct();
        $this->nytService = $nytService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = $this->argument('category') ?? 'home';
        $source = 'NewYorkTimes';

        try {
            $articles = $this->nytService->fetchArticles($query);

            if (empty($articles)) {
                $this->warn('No articles found.');
                return Command::SUCCESS;
            }

            $existingTitles = Article::selectRaw("JSON_UNQUOTE(details->'$.title') as title")
                ->pluck('title')
                ->toArray();

            foreach ($articles['results'] as $article) {

                if (in_array($article['title'], $existingTitles)) {
                    continue;
                }

                Article::create([
                    'source' => 'New York Times',
                    'date_published' => $article['published_date'],
                    'category' => ucwords($article['section']),
                    'api_source' => $source,
                    'details' => json_decode(json_encode($article), true)
                ]);
            }

            $this->info("Articles fetched successfully from $source.");
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
