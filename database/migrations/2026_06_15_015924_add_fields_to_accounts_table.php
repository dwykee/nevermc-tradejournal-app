<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            // Tambah kolom type (funded / challenge / personal) kalau belum ada
            if (! Schema::hasColumn('accounts', 'type')) {
                $table->string('type')->default('personal')->after('broker');
            }

            // Tambah kolom starting_balance kalau belum ada
            if (! Schema::hasColumn('accounts', 'starting_balance')) {
                $table->decimal('starting_balance', 15, 2)->default(0)->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            foreach (['starting_balance', 'type'] as $column) {
                if (Schema::hasColumn('accounts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
