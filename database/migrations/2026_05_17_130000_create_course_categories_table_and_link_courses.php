<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('course_category_id')->nullable()->after('title')->constrained('course_categories')->nullOnDelete();
        });

        $legacyCourses = DB::table('courses')
            ->select('id', 'category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->get();

        $categoryIdsByName = [];

        foreach ($legacyCourses as $course) {
            $name = trim((string) $course->category);
            if ($name === '') {
                continue;
            }

            if (!isset($categoryIdsByName[$name])) {
                $existing = DB::table('course_categories')->where('name', $name)->first();
                if ($existing) {
                    $categoryIdsByName[$name] = $existing->id;
                } else {
                    $categoryIdsByName[$name] = DB::table('course_categories')->insertGetId([
                        'name' => $name,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('courses')
                ->where('id', $course->id)
                ->update(['course_category_id' => $categoryIdsByName[$name]]);
        }
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_category_id');
        });

        Schema::dropIfExists('course_categories');
    }
};
