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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'username');
            $table->string('first_name')->nullable()->after('remember_token');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('surname')->after('middle_name');
            $table->string('mobile_number')->unique()->after('surname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('username', 'name');
            $table->dropColumn(['first_name', 'middle_name', 'surname', 'mobile_number']);
        });
    }
};
