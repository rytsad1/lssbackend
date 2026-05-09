<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_variant_id')
                ->constrained('item_variants')
                ->restrictOnDelete();

            $table->string('inventory_number')->nullable()->unique();
            $table->string('serial_number')->nullable()->unique();
            $table->string('imei')->nullable()->unique();

            $table->enum('status', [
                'in_stock',
                'reserved',
                'issued',
                'temporary_issued',
                'returned',
                'repair',
                'written_off',
                'lost'
            ])->default('in_stock');

            // Kol kas laikom ryšį į tavo esamą user lentelę per legacy ID
            $table->unsignedInteger('assigned_user_id')->nullable();
            $table->unsignedInteger('assigned_department_id')->nullable();

            $table->date('expiration_date')->nullable();

            $table->timestamp('issued_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('written_off_at')->nullable();

            $table->text('write_off_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['item_variant_id', 'status']);
            $table->index(['assigned_user_id']);
            $table->index(['assigned_department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_units');
    }
};
