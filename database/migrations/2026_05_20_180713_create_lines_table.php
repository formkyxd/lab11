<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lines', function (Blueprint $table) {

            $table->id();

            $table->string('code', 50);

            $table->time('start_time_operation');

            $table->time('end_time_operation');

            $table->string('type', 30);

            $table->string('map', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lines');
    }
};
