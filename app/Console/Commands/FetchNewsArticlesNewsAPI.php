<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use jcobhams\NewsApi\NewsApi;

use function PHPUnit\Framework\isNull;

class FetchNewsArticlesNewsAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles-news-api {category?} {--limit=10} {--page=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update articles from News API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $newsApi = new NewsApi(config('services.newsapi.api_key'));
            $source = 'NewsAPI';
            $category = $this->argument('category') ?? 'general';
            $limit = (int)$this->option('limit');
            $page = (int)$this->option('page');
            $articles = $newsApi->getTopHeadLines(null, null, 'us', $category, $limit, $page);

            if (empty($articles->articles)) {
                $this->info("No news articles found from $source.");
                return Command::SUCCESS;
            }

            $existingTitles = Article::selectRaw("JSON_UNQUOTE(details->'$.title') as title")
                ->pluck('title')
                ->toArray();

            foreach ($articles->articles as $article) {
                if (in_array($article->title, $existingTitles)) {
                    continue;
                }

                Article::create([
                    'source' => $article->source->name,
                    'date_published' => $article->publishedAt,
                    'category' => ucwords($category),
                    'api_source' => $source,
                    'details' => json_decode(json_encode($article), true)
                ]);
            }

            $this->info("Articles fetched successfully from $source.");
        } catch (\Exception $e) {
            $this->error("Unable to fetch articles: " . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
