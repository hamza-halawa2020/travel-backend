<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->unsignedTinyInteger('age')->nullable()->after('email');
            $table->string('country')->nullable()->after('age');
            $table->string('course')->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['email', 'age', 'country', 'course']);
        });
    }
};
