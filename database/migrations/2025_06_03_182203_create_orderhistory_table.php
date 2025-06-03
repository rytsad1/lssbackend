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
        Schema::create('orderhistory', function (Blueprint $table) {
            $table->date('Date')->nullable();
            $table->integer('id_OrderHistory', true);
            $table->integer('fkOrderid_Order')->index('orderhistory_ibfk_order');
            $table->integer('PerformedByUserid')->nullable()->index('orderhistory_ibfk_user');
            $table->string('Action', 100)->nullable();
            $table->text('Comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderhistory');
    }
};
