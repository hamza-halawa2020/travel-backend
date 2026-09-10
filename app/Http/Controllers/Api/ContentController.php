<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function siteSettings(): JsonResponse
    {
        return response()->json(ContentBlock::payloadFor('site-settings'));
    }

    public function home(): JsonResponse
    {
        return response()->json(ContentBlock::payloadFor('home-content'));
    }

    public function journal(): JsonResponse
    {
        return response()->json(ContentBlock::payloadFor('journal-content'));
    }

    public function articles(): JsonResponse
    {
        return response()->json(ContentBlock::payloadFor('journal-articles'));
    }

    public function article(string $slug): JsonResponse
    {
        $article = collect(ContentBlock::payloadFor('journal-articles'))
            ->firstWhere('slug', $slug);

        abort_unless($article, 404, 'Article not found.');

        return response()->json($article);
    }
}
