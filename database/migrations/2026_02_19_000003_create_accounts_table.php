<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('mother_account_id')->nullable()->constrained('mother_accounts')->nullOnDelete();
            $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
            $table->integer('plan_duration_days');
            $table->date('plan_start_date');
            $table->date('plan_expiry_date');
            $table->enum('status', ['active', 'unassigned', 'cooldown', 'deleted'])->default('active');
            $table->datetime('assigned_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('plan_expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
