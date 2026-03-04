<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 13, 2);
            $table->timestamp('recorded_at');
            $table->index(['subscription_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_history');
    }
};
