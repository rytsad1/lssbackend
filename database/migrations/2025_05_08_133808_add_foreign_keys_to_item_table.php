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
        Schema::table('item', function (Blueprint $table) {
            $table->foreign(['fkOrderHistoryid_OrderHistory'], 'item_ibfk_1')->references(['id_OrderHistory'])->on('orderhistory')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkOrderItemid_OrderItem'], 'item_ibfk_2')->references(['id_OrderItem'])->on('orderitem')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item', function (Blueprint $table) {
            $table->dropForeign('item_ibfk_1');
            $table->dropForeign('item_ibfk_2');
        });
    }
};
