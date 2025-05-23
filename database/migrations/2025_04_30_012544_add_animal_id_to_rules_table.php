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
        Schema::table('rules', function (Blueprint $table) {
            $table->foreignId('animal_id')
                ->nullable()
                ->after('id')
                ->constrained('animals')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('rules', function (Blueprint $table) {
            $table->dropForeign(['animal_id']);
            $table->dropColumn('animal_id');
        });
    }
};
