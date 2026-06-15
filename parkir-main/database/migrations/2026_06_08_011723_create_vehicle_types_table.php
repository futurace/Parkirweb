<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('rate_per_hour')->default(0);
            $table->unsignedInteger('first_hour_charges')->default(0);
            $table->unsignedInteger('next_hourly_charges')->default(0);
            $table->unsignedInteger('max_cost_per_day')->default(0);
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
