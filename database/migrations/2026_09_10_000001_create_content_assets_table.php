<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('content_assets')) {
            return;
        }

        Schema::create('content_assets', function (Blueprint $table) {
            $table->id();
            $table->text('original_url');
            $table->string('public_path');
            $table->string('source', 20);
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('checksum', 64)->nullable();
            $table->timestamps();

            $table->unique('public_path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_assets');
    }
};
