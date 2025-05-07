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
        Schema::create('billoflading', function (Blueprint $table) {
            $table->date('Date')->nullable();
            $table->double('Sum');
            $table->integer('Type')->index('type');
            $table->integer('id_BillOfLading', true);
            $table->integer('fkOrderid_Order')->nullable()->unique('fkorderid_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billoflading');
    }
};
