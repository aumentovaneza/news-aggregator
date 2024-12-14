<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:fetch-news-articles-news-api')->daily();
Schedule::command('app:fetch-news-articles-nyt')->daily();
Schedule::command('app:fetch-news-articles-the-guardian')->daily();
