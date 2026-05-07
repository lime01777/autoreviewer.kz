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
        Schema::table('dealerships', function (Blueprint $table) {
            $table->string('legal_name')->nullable()->after('title');
            $table->string('brand')->nullable()->after('type');
            $table->json('brands')->nullable()->after('brand');
            $table->boolean('is_official_dealer')->default(false)->after('brands');
            $table->string('country')->default('Kazakhstan')->after('is_official_dealer');
            
            $table->string('source_url')->nullable();
            $table->string('source_name')->nullable();
            $table->timestamp('source_checked_at')->nullable();
            $table->boolean('data_verified')->default(false);
            $table->enum('data_status', ['draft', 'verified', 'needs_review'])->default('draft');
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dealerships', function (Blueprint $table) {
            $table->dropColumn([
                'legal_name', 'brand', 'brands', 'is_official_dealer', 'country',
                'source_url', 'source_name', 'source_checked_at', 'data_verified',
                'data_status', 'notes'
            ]);
        });
    }
};
