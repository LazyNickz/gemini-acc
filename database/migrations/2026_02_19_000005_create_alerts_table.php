<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['mother_expiry', 'plan_expiry', 'transfer_needed']);
            $table->string('alertable_type');
            $table->unsignedBigInteger('alertable_id');
            $table->text('message');
            $table->enum('severity', ['warning', 'critical'])->default('warning');
            $table->boolean('resolved')->default(false);
            $table->datetime('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['alertable_type', 'alertable_id']);
            $table->index('resolved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
