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
        Schema::create('order', function (Blueprint $table) {
            $table->date('Date')->nullable();
            $table->integer('State')->index('state');
            $table->integer('Type')->index('type');
            $table->integer('id_Order', true);
            $table->integer('fkOrderHistoryid_OrderHistory')->nullable()->unique('fkorderhistoryid_orderhistory');
            $table->integer('fkUserid_User')->nullable()->index('fkuserid_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
