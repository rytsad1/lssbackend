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
        Schema::table('order', function (Blueprint $table) {
            $table->foreign(['State'], 'order_ibfk_1')->references(['id_OrderStatus'])->on('orderstatus')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['Type'], 'order_ibfk_2')->references(['id_OrderType'])->on('ordertype')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkOrderHistoryid_OrderHistory'], 'order_ibfk_3')->references(['id_OrderHistory'])->on('orderhistory')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkUserid_User'], 'order_ibfk_4')->references(['id_User'])->on('user')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropForeign('order_ibfk_1');
            $table->dropForeign('order_ibfk_2');
            $table->dropForeign('order_ibfk_3');
            $table->dropForeign('order_ibfk_4');
        });
    }
};
