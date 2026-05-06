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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealership_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('author_name');
            $table->string('author_phone')->nullable();
            $table->string('author_email')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('text');
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('admin_comment')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
