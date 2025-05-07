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
        Schema::table('role', function (Blueprint $table) {
            $table->foreign(['fkUserRoleid_UserRole'], 'role_ibfk_1')->references(['id_UserRole'])->on('userrole')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkRolePremissionid_RolePremission'], 'role_ibfk_2')->references(['id_RolePremission'])->on('rolepremission')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role', function (Blueprint $table) {
            $table->dropForeign('role_ibfk_1');
            $table->dropForeign('role_ibfk_2');
        });
    }
};
