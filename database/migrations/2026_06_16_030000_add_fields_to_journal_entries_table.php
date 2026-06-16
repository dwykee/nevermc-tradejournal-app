<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('journal_entries', 'went_well')) {
                $table->text('went_well')->nullable()->after('title');
            }
            if (!Schema::hasColumn('journal_entries', 'went_wrong')) {
                $table->text('went_wrong')->nullable()->after('went_well');
            }
            if (!Schema::hasColumn('journal_entries', 'lessons')) {
                $table->text('lessons')->nullable()->after('went_wrong');
            }
            if (!Schema::hasColumn('journal_entries', 'image')) {
                $table->string('image')->nullable()->after('mood');
            }
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropColumn(['went_well', 'went_wrong', 'lessons', 'image']);
        });
    }
};