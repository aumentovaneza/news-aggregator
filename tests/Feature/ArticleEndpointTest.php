<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class ArticleEndpointTest extends TestCase
{
    use RefreshDatabase;

    // Test the index endpoint for articles
    public function test_index_articles()
    {
        // Arrange: Seed some articles and create and authenticate a user
        Article::factory(10)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);  // Authenticate the user

        // Act: Send GET request to the articles index endpoint
        $response = $this->getJson(route('articles.index'));

        // Assert: Check if the response is successful
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'current_page',
                'per_page',
                'total',
                'last_page',
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ]
        ]);
    }

    // Test the search endpoint for articles
    public function test_search_articles()
    {
        // Arrange: Create some articles and authenticate a user
        Article::factory()->create(['title' => 'Tech News']);
        Article::factory()->create(['title' => 'Health News']);
        $user = User::factory()->create();
        Sanctum::actingAs($user);  // Authenticate the user

        // Act: Send GET request to the search endpoint with a query
        $response = $this->getJson(route('articles.search', [
            'query' => 'Tech',
            'column' => 'title',
            'limit' => 10,
        ]));

        // Assert: Check if the response contains the relevant article
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['title' => 'Tech News']);
    }

    // Test the show endpoint for a single article
    public function test_show_article()
    {
        // Arrange: Create an article and authenticate a user
        $article = Article::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);  // Authenticate the user

        // Act: Send GET request to show the article
        $response = $this->getJson(route('articles.show', $article));

        // Assert: Check if the response contains the article data
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['title' => $article->title]);
    }

    // Test the user feed setting creation
    public function test_create_user_feed_setting()
    {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);  // Authenticate the user

        // Act: Send POST request to the create user feed setting endpoint
        $response = $this->postJson(route('user.feed.setting.create'), [
            'sort_date_by' => 'desc',
            'show_source_from' => 'TheGuardian'
        ]);

        // Assert: Check if the response is successful
        $response->assertStatus(Response::HTTP_CREATED);
    }

    // Test the user feed index endpoint
    public function test_user_feed_index()
    {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);  // Authenticate the user

        // Act: Send GET request to the user feed index endpoint
        $response = $this->getJson(route('user.feed.index'));

        // Assert: Check if the response is successful
        $response->assertStatus(Response::HTTP_OK);
    }
}
