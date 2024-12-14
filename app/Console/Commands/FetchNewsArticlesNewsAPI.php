<?php

namespace App\Console\Commands;

use App\Models\Article;
use Carbon\Carbon;
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
    protected $signature = 'app:fetch-news-articles-news-api';

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

            $newsApi = new NewsApi(getenv('NEWSAPI_KEY'));
            $source = 'NewsAPI';
            $articles = $newsApi->getTopHeadLines(null, null, 'us', null, 10, 1);

            if (empty($articles->articles)) {
                $this->info("No news articles found from $source.");
                return;
            }

            $existingTitles = Article::selectRaw("JSON_UNQUOTE(details->'$.title') as title")
            ->pluck('title')
            ->toArray();

            foreach ($articles->articles as $article) {
                if (in_array($article->title, $existingTitles)) {
                    continue;
                }

                Article::create([
                    'source' => $source,
                    'details' => json_decode(json_encode($article), true)
                ]);
            }

            $this->info("Articles fetched successfully from $source.");
        } catch (\Exception $e) {
            $this->error("Unable to fetch articles: " . $e->getMessage());
        }
    }
}
