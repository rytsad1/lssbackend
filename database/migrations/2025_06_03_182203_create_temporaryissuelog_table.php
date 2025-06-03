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
        Schema::create('temporaryissuelog', function (Blueprint $table) {
            $table->integer('id_TemporaryIssueLog', true);
            $table->integer('fkItemid_Item')->index('fkitemid_item');
            $table->integer('fkUserid_User')->index('fkuserid_user');
            $table->date('IssuedDate');
            $table->date('ReturnedDate')->nullable();
            $table->text('Comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporaryissuelog');
    }
};
