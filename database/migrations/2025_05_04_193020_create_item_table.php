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
        Schema::create('item', function (Blueprint $table) {
            $table->string('Name')->nullable();
            $table->string('Description')->nullable();
            $table->double('Price')->nullable();
            $table->string('InventoryNumber')->nullable();
            $table->string('UnitOfMeasure')->nullable();
            $table->double('Quantity')->nullable();
            $table->integer('id_Item', true);
            $table->integer('fkOrderHistoryid_OrderHistory')->nullable()->index('fkorderhistoryid_orderhistory');
            $table->integer('fkOrderItemid_OrderItem')->nullable()->index('fkorderitemid_orderitem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
