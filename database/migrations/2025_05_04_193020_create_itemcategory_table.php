<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('itemcategory', function (Blueprint $table) {
            $table->string('Description')->nullable();
            $table->integer('Name')->index('name');
            $table->integer('id_ItemCategory', true);
            $table->integer('fkItemid_Item')->nullable()->unique('fkitemid_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemcategory');
    }
};
