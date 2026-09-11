<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use App\Models\JournalArticle;
use App\Models\JournalArticleFaq;
use App\Models\JournalArticleSection;
use App\Models\JournalCategory;
use App\Models\JournalSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JournalSeeder extends Seeder
{
    public function run(): void
    {
        $journalContent = ContentBlock::payloadFor('journal-content');
        $articles = ContentBlock::payloadFor('journal-articles');

        JournalSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'page_eyebrow' => $journalContent['page']['eyebrow'] ?? 'The Journal',
                'page_title' => $journalContent['page']['title'] ?? 'Notes from the front cabin.',
                'page_body' => $journalContent['page']['body'] ?? '',
                'filter_label' => $journalContent['filters']['label'] ?? 'Filter articles by category',
                'default_category_slug' => $journalContent['filters']['defaultCategoryId'] ?? 'all',
            ],
        );

        $categoryLabels = collect($articles)
            ->pluck('category')
            ->filter()
            ->unique()
            ->values();

        $categories = [];
        foreach ($categoryLabels as $label) {
            $categories[$label] = JournalCategory::query()->updateOrCreate(
                ['slug' => Str::slug($label)],
                [
                    'label' => $label,
                ],
            );
        }

        $articleModels = [];
        foreach ($articles as $articleData) {
            $category = $categories[$articleData['category']];
            $cta = $articleData['cta'] ?? [];

            $article = JournalArticle::query()->updateOrCreate(
                ['slug' => $articleData['slug']],
                [
                    'journal_category_id' => $category->id,
                    'read_time' => $articleData['readTime'],
                    'title' => $articleData['title'],
                    'dek' => $articleData['dek'],
                    'excerpt' => $articleData['excerpt'],
                    'image' => $articleData['image'],
                    'alt' => $articleData['alt'],
                    'author' => $articleData['author'],
                    'updated_label' => $articleData['updated'],
                    'cta_title' => $cta['title'] ?? null,
                    'cta_body' => $cta['body'] ?? null,
                    'cta_label' => $cta['label'] ?? null,
                    'cta_href' => $cta['href'] ?? null,
                    'is_published' => true,
                ],
            );

            $articleModels[$articleData['slug']] = $article;
            $this->syncSections($article, $articleData['sections'] ?? []);
            $this->syncFaqs($article, $articleData['faqs'] ?? []);
        }

        foreach ($articles as $articleData) {
            $article = $articleModels[$articleData['slug']];

            DB::table('journal_article_related')
                ->where('journal_article_id', $article->id)
                ->delete();

            foreach (($articleData['relatedArticles'] ?? []) as $relatedData) {
                $relatedArticle = $articleModels[$relatedData['slug']] ?? null;
                if (! $relatedArticle || $relatedArticle->is($article)) {
                    continue;
                }

                DB::table('journal_article_related')->updateOrInsert(
                    [
                        'journal_article_id' => $article->id,
                        'related_journal_article_id' => $relatedArticle->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            }
        }
    }

    private function syncSections(JournalArticle $article, array $sections): void
    {
        $article->sections()->delete();

        foreach ($sections as $sectionData) {
            $image = $sectionData['image'] ?? [];

            JournalArticleSection::query()->create([
                'journal_article_id' => $article->id,
                'heading' => $sectionData['heading'],
                'body' => $sectionData['body'] ?? [],
                'image_src' => $image['src'] ?? null,
                'image_alt' => $image['alt'] ?? null,
                'image_caption' => $image['caption'] ?? null,
                'pull_quote' => $sectionData['pullQuote'] ?? null,
                'bullets' => $sectionData['bullets'] ?? null,
            ]);
        }
    }

    private function syncFaqs(JournalArticle $article, array $faqs): void
    {
        $article->faqs()->delete();

        foreach ($faqs as $faqData) {
            JournalArticleFaq::query()->create([
                'journal_article_id' => $article->id,
                'question' => $faqData['question'],
                'answer' => $faqData['answer'],
            ]);
        }
    }
}
