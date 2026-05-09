<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kit_template_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kit_template_id')
                ->constrained('kit_templates')
                ->cascadeOnDelete();

            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            $table->decimal('required_quantity', 12, 3)->default(1);

            $table->boolean('size_sensitive')->default(false);
            $table->boolean('must_be_same_batch')->default(false);
            $table->boolean('must_be_compatible')->default(false);
            $table->boolean('prefer_fefo')->default(false);

            // Pvz. {"preferred_sizes":["M","L"],"min_expiry_days":30}
            $table->json('selection_rules')->nullable();

            $table->timestamps();

            $table->index(['kit_template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kit_template_items');
    }
};
