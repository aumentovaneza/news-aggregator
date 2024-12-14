<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleManagementController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10;

        $articles = Article::paginate($limit);

        return response()->json([
            'data' => $articles->items(),
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
        $query = $request->get('query'); // The search keyword
        $column = $request->get('column'); // Optional: column to search in

        // Validate input
        $request->validate([
            'query' => 'required|string|max:255',
            'column' => 'nullable|string|in:source,category,date_published', // Ensure column is valid
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
                    ->orWhere('date_published', 'LIKE', "%{$query}%");
            })
            ->get();

        // Return the results as JSON
        return response()->json($articles);
    }
}
