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
        Schema::table('user', function (Blueprint $table) {
            $table->foreign(['State'], 'user_ibfk_1')->references(['id_State'])->on('state')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkOrderHistoryid_OrderHistory'], 'user_ibfk_2')->references(['id_OrderHistory'])->on('orderhistory')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkBillOfLadingid_BillOfLading'], 'user_ibfk_3')->references(['id_BillOfLading'])->on('billoflading')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign('user_ibfk_1');
            $table->dropForeign('user_ibfk_2');
            $table->dropForeign('user_ibfk_3');
        });
    }
};
