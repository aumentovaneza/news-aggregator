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

### Docker Commands
To do any artisan commands use or if bash `./vendor/laravel/sail/bin/sail` does not work:
    
    docker exec -it <container_name> php artisan <artisan-command>

To stop the containers:

    bash ./vendor/laravel/sail/bin/sail down


### API Endpoints

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

### Running Tests

To run tests within the Sail container, execute:

    bash ./vendor/laravel/sail/bin/sail artisan test

    or

    docker exec -it <container name> php artisan test

To run a specific test:

    docker exec -it <container name> vendor/bin/phpunit --filter <TestClass>