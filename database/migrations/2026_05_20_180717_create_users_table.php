<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->char('gender', 2)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email', 50)->unique();
            $table->string('login', 40)->unique();
            $table->string('password', 255);
            $table->string('role')->default('user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
