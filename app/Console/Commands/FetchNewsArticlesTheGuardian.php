<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchNewsArticlesTheGuardian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles-the-guardian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update articles from The Guardian';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
