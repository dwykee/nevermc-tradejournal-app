<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trades', function (Blueprint $table) {
            if (! Schema::hasColumn('trades', 'status')) {
                $table->string('status')->default('closed')->after('net_pnl');
            }
            if (! Schema::hasColumn('trades', 'rating')) {
                $table->unsignedTinyInteger('rating')->nullable()->after('setup');
            }
            if (! Schema::hasColumn('trades', 'screenshot_before')) {
                $table->string('screenshot_before')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('trades', 'screenshot_after')) {
                $table->string('screenshot_after')->nullable()->after('screenshot_before');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trades', function (Blueprint $table) {
            foreach (['screenshot_after', 'screenshot_before', 'rating', 'status'] as $column) {
                if (Schema::hasColumn('trades', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
