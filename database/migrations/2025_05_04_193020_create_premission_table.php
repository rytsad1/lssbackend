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
        Schema::create('premission', function (Blueprint $table) {
            $table->string('Name')->nullable();
            $table->string('Description')->nullable();
            $table->integer('id_Premission', true);
            $table->integer('fkRolePremissionid_RolePremission')->nullable()->index('fkrolepremissionid_rolepremission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premission');
    }
};
