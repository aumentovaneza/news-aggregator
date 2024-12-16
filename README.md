# News Aggregator

A Laravel-based API for aggregating news articles from various sources. This application allows users to view, search, and manage articles, as well as customize their feed preferences.




## Features

- **Authentication**: Login, registration, and logout functionality with Laravel Sanctum.
- **Articles Management**: View, search, and display articles.
- **User Feed Settings**: Customize article feed preferences based on sources, categories, and sorting.




## Development Environment

This project uses [Laravel Sail](https://laravel.com/docs/8.x/sail) and [Docker](https://www.docker.com/) to manage the development environment.




### Requirements

- Docker
- Docker Compose




### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/aumentovaneza/news-aggregator.git
   cd news-aggregator

2. Copy the .env.example to .env and configure your environment settings:
    ```bash
    cp .env.example .env

3. Install Sail dependencies:
    ```bash
    bash ./vendor/laravel/sail/bin/sail up

4. Install project dependencies:
    ```bash
    bash ./vendor/laravel/sail/bin/sail composer install

5. Generate the application key:
    ```bash
    bash ./vendor/laravel/sail/bin/sail artisan key:generate

6. Run the database migrations and seeder:
    ```bash
    bash ./vendor/laravel/sail/bin/sail artisan migrate
    bash ./vendor/laravel/sail/bin/sail artisan db:seed




## Docker Commands
To do any artisan commands use or if bash `./vendor/laravel/sail/bin/sail` does not work:
    
    docker exec -it <container_name> php artisan <artisan-command>

To stop the containers:

    bash ./vendor/laravel/sail/bin/sail down





## API Endpoints

##Authentication##

- POST /auth/login: Generate API token for users.
- POST /auth/signup: Register a new user.

##Articles##
- GET /articles: Get a list of articles.
- GET /article/{id}: Get a single article by ID.
- GET /articles/search: Search articles by query and filter.

##User Settings##
- POST /setting: Create or update user feed settings.
- GET /user/feed: Retrieve the user's feed settings.



## Fetching Articles

### Add the API keys
There are 3 sources of articles for this tool, these are NewsAPI, The Guardian and New York Times. Sign-up for free from these and you should be able to get the keys. Here are their sites:

- NewsAPI - <https://newsapi.org/register>
- The Guardian - <https://newsapi.org/register>
- New York Times - <https://developer.nytimes.com/accounts/create>

### Running Command
To fetch articles manually from each of the sources, read through here:

###NewsAPI###
This command retrieves the top headlines for the live top headlines.

`app:fetch-news-articles-news-api {category?} {--limit=10} {--page=1}`

- category - The news category you want to pull. It could be: business, entertainment, general, health, science, sports, and technology. The default for this if you don't want to specify is general.
- limit - Is an optional parameter where you can set the number of results you want to pull. The default is 10.
- page - Is an optional parameter where you can set the page number.

###The Guardian###
This command retrieves headlines from The Guardian.

`app:fetch-news-articles-the-guardian {query?} {--limit=10}`

- query - You can fill this parameter to search for a specific topic.
- limit - Is an optional parameter where you can set the number of results you want to pull. The default is 10.

###New York Times###
This command retrieves top stores from New York Times.

`app:fetch-news-articles-nyt {category?}`

- category - You can set the news category you want to pull. It could be: arts, automobiles, books/review, business, fashion, food, health, home, insider, magazine, movies, nyregion, obituaries, opinion, politics, realestate, science, sports, sundayreview, technology, theater, t-magazine, travel, upshot, us, world. The default is home.

These commands are set to run daily.

## Running Tests

To run tests within the Sail container, execute:

    bash ./vendor/laravel/sail/bin/sail artisan test

    or

    docker exec -it <container name> php artisan test

To run a specific test:

    docker exec -it <container name> vendor/bin/phpunit --filter <TestClass>




## Swagger API Documentation

<https://app.swaggerhub.com/apis/VANEZAAUMENTO_1/news-aggregator/1.0.0>