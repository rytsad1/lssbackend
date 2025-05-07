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
        Schema::table('department', function (Blueprint $table) {
            $table->foreign(['fkUserid_User'], 'department_ibfk_1')->references(['id_User'])->on('user')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkBillOfLadingid_BillOfLading'], 'department_ibfk_2')->references(['id_BillOfLading'])->on('billoflading')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('department', function (Blueprint $table) {
            $table->dropForeign('department_ibfk_1');
            $table->dropForeign('department_ibfk_2');
        });
    }
};
