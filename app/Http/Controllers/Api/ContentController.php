<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\JournalArticle;
use App\Models\JournalCategory;
use App\Models\JournalSetting;
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
        $settings = JournalSetting::query()->first();
        $articles = $this->publishedArticles()->get();

        $categories = collect([
            [
                'id' => 'all',
                'label' => 'All Articles',
                'articles' => $articles->map->summaryPayload()->values()->all(),
            ],
        ]);

        $categoryFilters = JournalCategory::query()
            ->orderByDesc('id')
            ->with(['articles' => fn ($query) => $query->where('is_published', true)->orderByDesc('id')])
            ->get()
            ->map(fn (JournalCategory $category): array => [
                'id' => $category->slug,
                'label' => $category->label,
                'articles' => $category->articles->map->summaryPayload()->values()->all(),
            ]);

        return response()->json([
            'page' => [
                'eyebrow' => $settings?->page_eyebrow ?? 'The Journal',
                'title' => $settings?->page_title ?? 'Notes from the front cabin.',
                'body' => $settings?->page_body ?? '',
            ],
            'filters' => [
                'label' => $settings?->filter_label ?? 'Filter articles by category',
                'defaultCategoryId' => $settings?->default_category_slug ?? 'all',
                'categories' => $categories->merge($categoryFilters)->values()->all(),
            ],
        ]);
    }

    public function articles(): JsonResponse
    {
        return response()->json(
            $this->publishedArticles()
                ->with(['sections', 'faqs', 'relatedArticles.category'])
                ->get()
                ->map->fullPayload()
                ->values()
                ->all()
        );
    }

    public function article(string $slug): JsonResponse
    {
        $article = $this->publishedArticles()
            ->with(['sections', 'faqs', 'relatedArticles.category'])
            ->where('slug', $slug)
            ->first();

        abort_unless($article, 404, 'Article not found.');

        return response()->json($article->fullPayload());
    }

    private function publishedArticles()
    {
        return JournalArticle::query()
            ->where('is_published', true)
            ->with('category')
            ->orderByDesc('id');
    }
}
