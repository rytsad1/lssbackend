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
        Schema::create('department', function (Blueprint $table) {
            $table->string('Name')->nullable();
            $table->string('Description')->nullable();
            $table->string('Address')->nullable();
            $table->integer('id_Department', true);
            $table->integer('fkUserid_User')->nullable()->unique('fkuserid_user');
            $table->integer('fkBillOfLadingid_BillOfLading')->nullable()->index('fkbillofladingid_billoflading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department');
    }
};
