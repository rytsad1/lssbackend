<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->string('unit_of_measure', 50)->default('vnt');
            $table->boolean('is_expirable')->default(false);
            $table->boolean('is_asset')->default(false);
            $table->boolean('is_serialized')->default(false);
            $table->boolean('is_active')->default(true);

            // Susiejimui su sena lentele item
            $table->unsignedInteger('legacy_item_id')->nullable()->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
