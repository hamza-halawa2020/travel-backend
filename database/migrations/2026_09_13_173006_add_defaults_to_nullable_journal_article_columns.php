<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_articles', function (Blueprint $table) {
            $table->string('read_time')->default('')->change();
            $table->string('author')->default('')->change();
            $table->string('updated_label')->default('')->change();
        });
    }

    public function down(): void
    {
        Schema::table('journal_articles', function (Blueprint $table) {
            $table->string('read_time')->default(null)->change();
            $table->string('author')->default(null)->change();
            $table->string('updated_label')->default(null)->change();
        });
    }
};
