<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use jcobhams\NewsApi\NewsApi;

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
        $newsApi = new NewsApi(getenv('NEWSAPI_KEY'));
        $lastFiveDays = now()->subDays(5)->toDateString();

        $articles = $newsApi->getTopHeadLines(null,null,'us',null,10,1);
        dd($articles);
    }
}
