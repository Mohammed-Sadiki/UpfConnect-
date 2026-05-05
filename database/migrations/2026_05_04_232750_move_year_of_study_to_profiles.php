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
        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('year_of_study')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'year_of_study')) {
                $table->dropColumn('year_of_study');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('year_of_study')->nullable();
        });

        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'year_of_study')) {
                $table->dropColumn('year_of_study');
            }
        });
    }
};
