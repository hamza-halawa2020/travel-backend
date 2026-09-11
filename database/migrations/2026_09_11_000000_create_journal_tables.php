<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_eyebrow')->default('The Journal');
            $table->string('page_title');
            $table->text('page_body');
            $table->string('filter_label')->default('Filter articles by category');
            $table->string('default_category_slug')->default('all');
            $table->timestamps();
        });

        Schema::create('journal_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('journal_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_category_id')->constrained('journal_categories')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('read_time');
            $table->string('title');
            $table->text('dek');
            $table->text('excerpt');
            $table->string('image');
            $table->string('alt');
            $table->string('author');
            $table->string('updated_label');
            $table->string('cta_title')->nullable();
            $table->text('cta_body')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_href')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('journal_article_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_article_id')->constrained('journal_articles')->cascadeOnDelete();
            $table->string('heading');
            $table->json('body');
            $table->string('image_src')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('image_caption')->nullable();
            $table->text('pull_quote')->nullable();
            $table->json('bullets')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('journal_article_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_article_id')->constrained('journal_articles')->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('journal_article_related', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_article_id')->constrained('journal_articles')->cascadeOnDelete();
            $table->foreignId('related_journal_article_id')->constrained('journal_articles')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['journal_article_id', 'related_journal_article_id'], 'journal_related_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_article_related');
        Schema::dropIfExists('journal_article_faqs');
        Schema::dropIfExists('journal_article_sections');
        Schema::dropIfExists('journal_articles');
        Schema::dropIfExists('journal_categories');
        Schema::dropIfExists('journal_settings');
    }
};
