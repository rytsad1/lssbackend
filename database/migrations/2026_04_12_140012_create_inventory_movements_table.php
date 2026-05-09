<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_variant_id')
                ->constrained('item_variants')
                ->restrictOnDelete();

            $table->foreignId('stock_batch_id')
                ->nullable()
                ->constrained('stock_batches')
                ->nullOnDelete();

            $table->foreignId('asset_unit_id')
                ->nullable()
                ->constrained('asset_units')
                ->nullOnDelete();

            // Ryšiai į tavo dabartinę sistemą
            $table->unsignedInteger('legacy_user_id')->nullable();
            $table->unsignedInteger('legacy_department_id')->nullable();
            $table->unsignedInteger('legacy_order_id')->nullable();

            $table->enum('movement_type', [
                'initial_load',
                'manual_adjustment',
                'receipt_sync',
                'issue',
                'temporary_issue',
                'return',
                'temporary_return',
                'writeoff',
                'inventory_gain',
                'inventory_loss',
                'reservation',
                'reservation_release'
            ]);

            // Teigiama arba neigiama reikšmė pagal logiką
            $table->decimal('quantity', 12, 3)->default(0);

            $table->timestamp('movement_date');
            $table->text('reason')->nullable();

            // Pvz. papildomas kontekstas algoritmams
            $table->json('context')->nullable();

            $table->timestamps();

            $table->index(['item_variant_id', 'movement_date']);
            $table->index(['movement_type', 'movement_date']);
            $table->index(['legacy_department_id', 'movement_date']);
            $table->index(['legacy_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
