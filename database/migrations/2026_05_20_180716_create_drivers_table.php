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
        Schema::create('drivers', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->date('birth_date');

            $table->string('email');

            $table->string('phone');

            $table->string('avatar')
                ->nullable();

            $table->foreignId('vehicle_id')
                ->nullable()
                ->unique()
                ->constrained('vehicles')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
