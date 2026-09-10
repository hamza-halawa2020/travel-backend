<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
        });

        $usedSlugs = [];

        DB::table('posts')
            ->select('id', 'title')
            ->orderBy('id')
            ->get()
            ->each(function ($post) use (&$usedSlugs) {
                $baseSlug = $this->slugify($post->title);
                $slug = $baseSlug;
                $counter = 2;

                while (isset($usedSlugs[$slug])) {
                    $suffix = '-' . $counter++;
                    $slug = Str::limit($baseSlug, 255 - strlen($suffix), '') . $suffix;
                }

                $usedSlugs[$slug] = true;

                DB::table('posts')
                    ->where('id', $post->id)
                    ->update(['slug' => $slug]);
            });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'meta_title', 'meta_description']);
        });
    }

    private function slugify(string $title): string
    {
        $slug = trim(preg_replace('/[^\pL\pN]+/u', '-', mb_strtolower($title)), '-');

        return Str::limit($slug ?: 'post', 255, '');
    }
};
