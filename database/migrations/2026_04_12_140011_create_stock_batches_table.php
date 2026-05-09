<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_batches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_variant_id')
                ->constrained('item_variants')
                ->restrictOnDelete();

            $table->string('batch_number')->nullable();
            $table->date('received_date')->nullable();

            $table->decimal('quantity_initial', 12, 3)->default(0);
            $table->decimal('quantity_remaining', 12, 3)->default(0);

            $table->date('expiration_date')->nullable();

            // Pvz. rankinis importas, buhalterinė sistema, excel importas
            $table->string('source_reference')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['item_variant_id', 'expiration_date']);
            $table->index(['item_variant_id', 'quantity_remaining']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_batches');
    }
};
