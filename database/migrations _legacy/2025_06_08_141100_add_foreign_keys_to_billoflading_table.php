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
        Schema::table('billoflading', function (Blueprint $table) {
            $table->foreign(['Type'], 'billoflading_ibfk_1')->references(['id_OrderType'])->on('ordertype')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkOrderid_Order'], 'billoflading_ibfk_2')->references(['id_Order'])->on('order')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billoflading', function (Blueprint $table) {
            $table->dropForeign('billoflading_ibfk_1');
            $table->dropForeign('billoflading_ibfk_2');
        });
    }
};
