<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anomaly_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_variant_id')
                ->nullable()
                ->constrained('item_variants')
                ->nullOnDelete();

            $table->unsignedInteger('legacy_department_id')->nullable();
            $table->unsignedInteger('legacy_user_id')->nullable();

            $table->string('anomaly_type');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');

            $table->decimal('score', 10, 4)->nullable();
            $table->text('summary');
            $table->json('details')->nullable();

            $table->timestamp('detected_at');
            $table->boolean('is_resolved')->default(false);

            $table->timestamps();

            $table->index(['anomaly_type', 'detected_at']);
            $table->index(['legacy_department_id', 'detected_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anomaly_events');
    }
};
