<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (! Schema::hasColumn('locations', 'name')) {
                $table->string('name')->after('id');
            }

            if (! Schema::hasColumn('locations', 'capacity')) {
                $table->unsignedInteger('capacity')->default(0)->after('name');
            }

            if (! Schema::hasColumn('locations', 'description')) {
                $table->text('description')->nullable()->after('capacity');
            }
        });

        Schema::table('vehicle_types', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicle_types', 'name')) {
                $table->string('name')->after('id');
            }

            if (! Schema::hasColumn('vehicle_types', 'rate_per_hour')) {
                $table->unsignedInteger('rate_per_hour')->default(0)->after('name');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'vehicle_type_id')) {
                $table->foreignId('vehicle_type_id')->after('id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('transactions', 'location_id')) {
                $table->foreignId('location_id')->after('vehicle_type_id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('transactions', 'plate_number')) {
                $table->string('plate_number', 20)->after('location_id');
            }

            if (! Schema::hasColumn('transactions', 'owner_name')) {
                $table->string('owner_name')->nullable()->after('plate_number');
            }

            if (! Schema::hasColumn('transactions', 'entry_time')) {
                $table->dateTime('entry_time')->after('owner_name');
            }

            if (! Schema::hasColumn('transactions', 'exit_time')) {
                $table->dateTime('exit_time')->nullable()->after('entry_time');
            }

            if (! Schema::hasColumn('transactions', 'status')) {
                $table->enum('status', ['parked', 'finished'])->default('parked')->after('exit_time');
            }

            if (! Schema::hasColumn('transactions', 'total_price')) {
                $table->unsignedInteger('total_price')->default(0)->after('status');
            }

            if (! Schema::hasColumn('transactions', 'notes')) {
                $table->text('notes')->nullable()->after('total_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['notes', 'total_price', 'status', 'exit_time', 'entry_time', 'owner_name', 'plate_number'] as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('transactions', 'location_id')) {
                $table->dropConstrainedForeignId('location_id');
            }

            if (Schema::hasColumn('transactions', 'vehicle_type_id')) {
                $table->dropConstrainedForeignId('vehicle_type_id');
            }
        });

        Schema::table('vehicle_types', function (Blueprint $table) {
            foreach (['rate_per_hour', 'name'] as $column) {
                if (Schema::hasColumn('vehicle_types', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('locations', function (Blueprint $table) {
            foreach (['description', 'capacity', 'name'] as $column) {
                if (Schema::hasColumn('locations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
