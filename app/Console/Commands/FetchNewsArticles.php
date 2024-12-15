<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use jcobhams\NewsApi\NewsApi;
use App\Services\NYTimesService;
use App\Services\GuardianService;
class FetchNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles {source} {category?} {query?} {--limit=10} {--page=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $nytService;
    protected $guardianService;

    public function __construct(NYTimesService $nytService, GuardianService $guardianService)
    {
        parent::__construct();
        $this->nytService = $nytService;
        $this->guardianService = $guardianService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->argument('source');
        $limit = (int) $this->option('limit');
        $page = (int) $this->option('page');
        $articles = null;
        $existingTitles = Article::select(columns: 'title')->toArray();

        try {

            switch ($source) {
                case 'NewsAPI':
                    $newsApi = new NewsApi(config('services.newsapi.api_key'));

                    $category = $this->argument('category') ?? 'general';
                    $articles = $newsApi->getTopHeadLines(null, null, 'us', $category, $limit, $page);

                    if (empty($articles->articles)) {
                        $this->info("No news articles found from $source.");
                        return Command::SUCCESS;
                    }

                    foreach ($articles->articles as $article) {
                        if (in_array($article->title, $existingTitles)) {
                            continue;
                        }

                        Article::create([
                            'source' => $article->source->name,
                            'title' => $article->title,
                            'date_published' => $article->publishedAt,
                            'category' => ucwords($category),
                            'api_source' => $source,
                            'details' => json_decode(json_encode([
                                'author' => $article->author,
                                'description' => $article->description,
                                'url' => $article->url
                            ]), true)
                        ]);
                    }

                    break;
                case 'NYTimes':
                    $category = $this->argument('category') ?? 'home';
                    $articles = $this->nytService->fetchArticles($category);

                    if (empty($articles['results'])) {
                        $this->info("No news articles found from $source.");
                        return Command::SUCCESS;
                    }

                    foreach ($articles['results'] as $article) {

                        if (in_array($article['title'], $existingTitles)) {
                            continue;
                        }

                        Article::create([
                            'source' => 'New York Times',
                            'title' => $article['title'],
                            'date_published' => $article['published_date'],
                            'category' => ucwords($article['section']),
                            'api_source' => $source,
                            'details' => json_decode(json_encode([
                                'subsection' => $article['author'],
                                'abstract' => $article['abstract'],
                                'url' => $article['url'],
                                'uri' => $article['uri'],
                                'byline' => $article['byline'],
                            ]), true)
                        ]);
                    }

                    break;
                case 'TheGuardian':
                    $query = $this->argument('query') ?? '';

                    $articles = $this->guardianService->fetchArticles($query, $limit);
                    if (empty($articles)) {
                        $this->warn('No articles found.');
                        return Command::SUCCESS;
                    }

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
                    break;
                default:
                    break;
            }

            $this->info("Articles fetched successfully from $source.");

        } catch (\Exception $e) {
            $this->error("Unable to fetch articles: " . $e->getMessage());
        }

    }
}
