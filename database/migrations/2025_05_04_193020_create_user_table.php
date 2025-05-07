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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('id_User')->primary();
            $table->string('Name')->nullable();
            $table->string('Surname')->nullable();
            $table->string('Email')->nullable();
            $table->string('Username')->nullable();
            $table->string('Password')->nullable();
            $table->integer('State')->index('state');
            $table->integer('fkOrderHistoryid_OrderHistory')->nullable()->unique('fkorderhistoryid_orderhistory');
            $table->integer('fkBillOfLadingid_BillOfLading')->nullable()->index('fkbillofladingid_billoflading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
