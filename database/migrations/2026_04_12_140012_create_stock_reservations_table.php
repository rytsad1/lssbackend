<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_reservations', function (Blueprint $table) {
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

            $table->unsignedInteger('legacy_user_id')->nullable();
            $table->unsignedInteger('legacy_department_id')->nullable();
            $table->unsignedInteger('legacy_order_id')->nullable();

            $table->decimal('quantity_reserved', 12, 3)->default(0);

            $table->enum('status', [
                'active',
                'fulfilled',
                'cancelled',
                'expired'
            ])->default('active');

            $table->timestamp('reserved_until')->nullable();
            $table->text('reason')->nullable();

            $table->timestamps();

            $table->index(['item_variant_id', 'status']);
            $table->index(['legacy_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_reservations');
    }
};
