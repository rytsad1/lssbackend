<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_measurements', function (Blueprint $table) {
            $table->id();

            // ryšys į tavo seną user lentelę
            $table->Integer('user_id');

            // standartiniai dydžiai
            $table->string('clothing_size', 20)->nullable();   // S, M, L, XL, XXL
            $table->string('shoe_size', 10)->nullable();       // 42, 43...
            $table->string('head_size', 10)->nullable();       // 56, 58...
            $table->string('glove_size', 10)->nullable();      // 9, 10...

            // papildomi matavimai
            $table->unsignedSmallInteger('height_cm')->nullable();
            $table->unsignedSmallInteger('weight_kg')->nullable();
            $table->unsignedSmallInteger('chest_cm')->nullable();
            $table->unsignedSmallInteger('waist_cm')->nullable();

            // bet kokie nestandartiniai matavimai
            $table->json('extra')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique('user_id');
            $table->foreign('user_id')->references('id_User')->on('user')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_measurements');
    }
};
