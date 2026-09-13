<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Make journal_article_id nullable on journal_article_faqs
        //    so FAQs can exist independently of any single article.
        Schema::table('journal_article_faqs', function (Blueprint $table) {
            $table->foreignId('journal_article_id')
                ->nullable()
                ->change();
        });

        // 2. Create the pivot table that links articles ↔ faqs (many-to-many).
        Schema::create('journal_article_faq_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_article_id')
                ->constrained('journal_articles')
                ->cascadeOnDelete();
            $table->foreignId('journal_article_faq_id')
                ->constrained('journal_article_faqs')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(
                ['journal_article_id', 'journal_article_faq_id'],
                'faq_article_pivot_unique'
            );
        });

        // 3. Migrate existing rows: copy each faq's article_id into the pivot,
        //    then clear the direct FK column so FAQs are truly standalone.
        DB::table('journal_article_faqs')
            ->whereNotNull('journal_article_id')
            ->orderBy('id')
            ->each(function ($faq) {
                DB::table('journal_article_faq_pivot')->insertOrIgnore([
                    'journal_article_id'     => $faq->journal_article_id,
                    'journal_article_faq_id' => $faq->id,
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);
            });

        // 4. Null-out the legacy FK column (pivot is now the source of truth).
        DB::table('journal_article_faqs')->update(['journal_article_id' => null]);
    }

    public function down(): void
    {
        // Reverse: restore journal_article_id from pivot, then drop pivot table.
        DB::table('journal_article_faq_pivot')->orderBy('id')->each(function ($row) {
            DB::table('journal_article_faqs')
                ->where('id', $row->journal_article_faq_id)
                ->update(['journal_article_id' => $row->journal_article_id]);
        });

        Schema::dropIfExists('journal_article_faq_pivot');

        Schema::table('journal_article_faqs', function (Blueprint $table) {
            $table->foreignId('journal_article_id')
                ->nullable(false)
                ->change();
        });
    }
};
