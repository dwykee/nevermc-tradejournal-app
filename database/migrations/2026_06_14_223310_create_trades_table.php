<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            $table->string('instrument');
            $table->enum('direction', ['long', 'short']);
            $table->decimal('quantity', 15, 4);
            $table->decimal('entry_price', 15, 4);
            $table->decimal('exit_price', 15, 4)->nullable();
            $table->timestamp('entry_time');
            $table->timestamp('exit_time')->nullable();
            $table->decimal('gross_pnl', 15, 2)->default(0);
            $table->decimal('commission', 15, 2)->default(0);
            $table->decimal('net_pnl', 15, 2)->default(0);
            $table->string('setup')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};