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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('uuid')->after('bexio_id')->nullable();
            $table->string('number')->after('uuid')->nullable();
            $table->renameColumn('contact', 'start_date');
            $table->dropColumn('owner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->string('owner')->nullable();
    }
};
