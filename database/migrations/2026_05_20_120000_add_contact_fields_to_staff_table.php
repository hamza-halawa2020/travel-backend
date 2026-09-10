<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('country')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'country']);
        });
    }
};
