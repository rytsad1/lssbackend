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
        Schema::create('rolepremission', function (Blueprint $table) {
            $table->integer('id_RolePremission', true);
            $table->integer('fk_Role')->nullable()->index('fk_rolepermission_role');
            $table->integer('fk_Permission')->nullable()->index('fk_rolepermission_permission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rolepremission');
    }
};
