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
            $table->string('type')->default('dealership')->after('status'); // official_dealer, dealership, shop, used, service, parts
        });

        Schema::create('brand_dealership', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('dealership_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_dealership');
        Schema::table('dealerships', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
