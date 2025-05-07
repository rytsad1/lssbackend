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
        Schema::table('rolepremission', function (Blueprint $table) {
            $table->foreign(['fk_Permission'], 'fk_rolepermission_permission')->references(['id_Premission'])->on('premission')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['fk_Role'], 'fk_rolepermission_role')->references(['id_Role'])->on('role')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rolepremission', function (Blueprint $table) {
            $table->dropForeign('fk_rolepermission_permission');
            $table->dropForeign('fk_rolepermission_role');
        });
    }
};
