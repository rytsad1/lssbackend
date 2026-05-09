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
        Schema::table('itemcategory', function (Blueprint $table) {
            $table->foreign(['Name'], 'itemcategory_ibfk_1')->references(['id_CategoryType'])->on('categorytype')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['fkItemid_Item'], 'itemcategory_ibfk_2')->references(['id_Item'])->on('item')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itemcategory', function (Blueprint $table) {
            $table->dropForeign('itemcategory_ibfk_1');
            $table->dropForeign('itemcategory_ibfk_2');
        });
    }
};
