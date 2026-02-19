<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mother_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->integer('max_capacity')->default(5);
            $table->integer('lifespan_days')->default(30);
            $table->date('start_date');
            $table->date('expiry_date');
            $table->enum('status', ['active', 'expired', 'archived'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mother_accounts');
    }
};
