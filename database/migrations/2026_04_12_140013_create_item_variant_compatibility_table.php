<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_variant_compatibility', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_variant_id')
                ->constrained('item_variants')
                ->cascadeOnDelete();

            $table->foreignId('compatibility_group_id')
                ->constrained('compatibility_groups')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['item_variant_id', 'compatibility_group_id'],
                'item_variant_compatibility_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_variant_compatibility');
    }
};
