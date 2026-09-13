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
        $settings  = JournalSetting::query()->first();
        $perPage   = min((int) request()->query('per_page', 6), 24);
        $page      = max((int) request()->query('page', 1), 1);
        $category  = request()->query('category', 'all');

        // Build the base query for the requested category
        if ($category === 'all' || empty($category)) {
            $query = $this->publishedArticles();
        } else {
            $cat = JournalCategory::where('slug', $category)->first();
            $query = $cat
                ? $this->publishedArticles()->where('journal_category_id', $cat->id)
                : $this->publishedArticles()->whereRaw('0 = 1');
        }

        $total    = $query->count();
        $lastPage = (int) ceil($total / $perPage);
        $articles = $query->forPage($page, $perPage)->get()->map->summaryPayload()->values()->all();

        // Category filter tabs (just ids/labels, no articles inside)
        $categoryTabs = collect([[
            'id'    => 'all',
            'label' => 'All Articles',
        ]])->merge(
            JournalCategory::query()->orderByDesc('id')->get()->map(fn (JournalCategory $c) => [
                'id'    => $c->slug,
                'label' => $c->label,
            ])
        )->values()->all();

        return response()->json([
            'page' => [
                'eyebrow' => $settings?->page_eyebrow ?? 'The Journal',
                'title'   => $settings?->page_title   ?? 'Notes from the front cabin.',
                'body'    => $settings?->page_body     ?? '',
            ],
            'filters' => [
                'label'             => $settings?->filter_label         ?? 'Filter articles by category',
                'defaultCategoryId' => $settings?->default_category_slug ?? 'all',
                'categories'        => $categoryTabs,
            ],
            'articles' => $articles,
            'pagination' => [
                'currentPage' => $page,
                'perPage'     => $perPage,
                'total'       => $total,
                'lastPage'    => max($lastPage, 1),
                'hasNext'     => $page < $lastPage,
                'hasPrev'     => $page > 1,
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
