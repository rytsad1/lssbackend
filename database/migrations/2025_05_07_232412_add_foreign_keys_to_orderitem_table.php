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
        Schema::table('orderitem', function (Blueprint $table) {
            $table->foreign(['fkOrderid_Order'], 'orderitem_ibfk_1')->references(['id_Order'])->on('order')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkItemid_Item'], 'orderitem_ibfk_item')->references(['id_Item'])->on('item')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orderitem', function (Blueprint $table) {
            $table->dropForeign('orderitem_ibfk_1');
            $table->dropForeign('orderitem_ibfk_item');
        });
    }
};
