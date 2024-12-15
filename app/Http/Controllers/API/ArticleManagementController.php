<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleManagementController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;

        $articles = Article::paginate($limit);

        return ArticleResource::collection($articles)
            ->additional([
                'meta' => [
                    'current_page' => $articles->currentPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                    'last_page' => $articles->lastPage(),
                ],
                'links' => [
                    'first' => $articles->url(1),
                    'last' => $articles->url($articles->lastPage()),
                    'prev' => $articles->previousPageUrl(),
                    'next' => $articles->nextPageUrl(),
                ],
            ]);
    }

    public function show(Article $article)
    {
        return response()->json($article);
    }

    public function search(Request $request)
    {
        // Get search query parameters
        $query = $request->get('query');
        $column = $request->get('column');
        $limit = $request->get('limit') ?? 10;

        // Validate input
        $request->validate([
            'query' => 'required|string|max:255',
            'column' => 'nullable|string|in:source,category,date_published,title',
        ]);

        // Query the database
        $articles = DB::table('articles')
            ->when($column, function ($q) use ($column, $query) {
                // If a column is specified
                return $q->where($column, 'LIKE', "%{$query}%");
            }, function ($q) use ($query) {
                // If no column specified, search in all relevant columns
                return $q->where('source', 'LIKE', "%{$query}%")
                    ->orWhere('category', 'LIKE', "%{$query}%")
                    ->orWhere('date_published', 'LIKE', "%{$query}%")
                    ->orWhere('title', 'LIKE', "%{$query}%");
            })
            ->paginate($limit);

        return ArticleResource::collection($articles)
            ->additional([
                'meta' => [
                    'current_page' => $articles->currentPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                    'last_page' => $articles->lastPage(),
                ],
                'links' => [
                    'first' => $articles->url(1),
                    'last' => $articles->url($articles->lastPage()),
                    'prev' => $articles->previousPageUrl(),
                    'next' => $articles->nextPageUrl(),
                ],
            ]);
        ;
    }
}
