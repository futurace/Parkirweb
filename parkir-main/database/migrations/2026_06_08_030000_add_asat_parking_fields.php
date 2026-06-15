<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (! Schema::hasColumn('locations', 'max_motorcycle')) {
                $table->unsignedInteger('max_motorcycle')->default(0)->after('capacity');
            }

            if (! Schema::hasColumn('locations', 'max_car')) {
                $table->unsignedInteger('max_car')->default(0)->after('max_motorcycle');
            }

            if (! Schema::hasColumn('locations', 'max_truck_bus_other')) {
                $table->unsignedInteger('max_truck_bus_other')->default(0)->after('max_car');
            }
        });

        Schema::table('vehicle_types', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicle_types', 'first_hour_charges')) {
                $table->unsignedInteger('first_hour_charges')->default(0)->after('rate_per_hour');
            }

            if (! Schema::hasColumn('vehicle_types', 'next_hourly_charges')) {
                $table->unsignedInteger('next_hourly_charges')->default(0)->after('first_hour_charges');
            }

            if (! Schema::hasColumn('vehicle_types', 'max_cost_per_day')) {
                $table->unsignedInteger('max_cost_per_day')->default(0)->after('next_hourly_charges');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'ticket_number')) {
                $table->string('ticket_number')->nullable()->after('location_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'ticket_number')) {
                $table->dropColumn('ticket_number');
            }
        });

        Schema::table('vehicle_types', function (Blueprint $table) {
            foreach (['max_cost_per_day', 'next_hourly_charges', 'first_hour_charges'] as $column) {
                if (Schema::hasColumn('vehicle_types', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('locations', function (Blueprint $table) {
            foreach (['max_truck_bus_other', 'max_car', 'max_motorcycle'] as $column) {
                if (Schema::hasColumn('locations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
