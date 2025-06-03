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
            $table->integer('Type')->nullable()->index('type');
            $table->integer('id_Order', true);
            $table->integer('fkUserid_User')->nullable()->index('fkuserid_user');
            $table->integer('fkOrderTypeid_OrderType')->index('order_ibfk_type');
            $table->integer('fkOrderStatusid_OrderStatus')->index('order_ibfk_status');
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
