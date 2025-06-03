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
        Schema::table('orderhistory', function (Blueprint $table) {
            $table->foreign(['fkOrderid_Order'], 'orderhistory_ibfk_order')->references(['id_Order'])->on('order')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['PerformedByUserid'], 'orderhistory_ibfk_user')->references(['id_User'])->on('user')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orderhistory', function (Blueprint $table) {
            $table->dropForeign('orderhistory_ibfk_order');
            $table->dropForeign('orderhistory_ibfk_user');
        });
    }
};
