<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->renameColumn('from', 'field_a');
            $table->renameColumn('to', 'field_b');
        });

        Schema::rename('contacts', 'enquiries');
    }

    public function down(): void
    {
        Schema::rename('enquiries', 'contacts');

        Schema::table('contacts', function (Blueprint $table) {
            $table->renameColumn('field_a', 'from');
            $table->renameColumn('field_b', 'to');
        });
    }
};
