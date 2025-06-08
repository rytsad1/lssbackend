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
        Schema::table('writeofflog', function (Blueprint $table) {
            $table->foreign(['fkItemid_Item'], 'writeofflog_ibfk_1')->references(['id_Item'])->on('item')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['HandledByUserid'], 'writeofflog_ibfk_2')->references(['id_User'])->on('user')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('writeofflog', function (Blueprint $table) {
            $table->dropForeign('writeofflog_ibfk_1');
            $table->dropForeign('writeofflog_ibfk_2');
        });
    }
};
