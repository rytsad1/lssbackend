<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_audits', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->unsignedInteger('legacy_department_id')->nullable();

            $table->date('audit_date');

            $table->enum('status', [
                'draft',
                'completed',
                'approved'
            ])->default('draft');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['legacy_department_id', 'audit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_audits');
    }
};
