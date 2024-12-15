<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ArticleResource;

class UserFeedSettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->setting) {
            $source = $user->setting->show_source_from;
            $orderBy = $user->setting->sort_date_by;

            $articles = DB::table('articles')
                ->when($source, function ($q) use ($source) {
                    // If a source is specified, filter the query
                    return $q->where('source', 'LIKE', "%{$source}%");
                }, function ($q) {
                    // If no source is specified, return the query as-is (all data)
                    return $q;
                })
                ->orderBy('created_at', $orderBy)
                ->paginate(5);
        } else {
            $articles = DB::table('articles')->paginate(5);
        }

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

    public function create(Request $request)
    {
        $request->validate([
            'sort_date_by' => 'required|string',
            'show_source_from' => 'required|string'
        ]);

        $user = auth()->user();

        $user->setting()->create([
            'sort_date_by' => $request->sort_date_by,
            'show_source_from' => $request->show_source_from
        ]);

        return response()->json(['message' => 'Successfully saved your setting!'], 201);
    }
}
