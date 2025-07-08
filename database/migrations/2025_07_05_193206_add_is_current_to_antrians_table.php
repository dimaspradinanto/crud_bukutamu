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
        Schema::table('antrians', function (Blueprint $table) {
            $table->boolean('is_current')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropColumn('is_current');
        });
    }
};
