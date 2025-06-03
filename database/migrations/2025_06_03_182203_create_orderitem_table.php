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
        Schema::create('orderitem', function (Blueprint $table) {
            $table->integer('Quantity');
            $table->integer('id_OrderItem', true);
            $table->integer('fkOrderid_Order')->nullable()->index('fkorderid_order');
            $table->integer('fkItemid_Item')->index('orderitem_ibfk_item');
            $table->integer('ReturnedQuantity')->nullable()->default(0);
            $table->text('WriteOffReason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderitem');
    }
};
