<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            $table->string('sku')->unique();
            $table->string('name');

            $table->string('size', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('model', 100)->nullable();

            // Pvz. {"gender":"male","season":"winter","battery_type":"AA"}
            $table->json('attributes')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['item_id', 'size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_variants');
    }
};
