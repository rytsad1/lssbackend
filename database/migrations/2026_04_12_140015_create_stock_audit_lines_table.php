<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_audit_lines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stock_audit_id')
                ->constrained('stock_audits')
                ->cascadeOnDelete();

            $table->foreignId('item_variant_id')
                ->constrained('item_variants')
                ->restrictOnDelete();

            $table->foreignId('stock_batch_id')
                ->nullable()
                ->constrained('stock_batches')
                ->nullOnDelete();

            $table->decimal('system_quantity', 12, 3)->default(0);
            $table->decimal('physical_quantity', 12, 3)->default(0);
            $table->decimal('difference_quantity', 12, 3)->default(0);

            $table->text('comment')->nullable();

            $table->timestamps();

            $table->index(['stock_audit_id', 'item_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_audit_lines');
    }
};
