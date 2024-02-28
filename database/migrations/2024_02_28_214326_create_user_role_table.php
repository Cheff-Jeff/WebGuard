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
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['super-admin', 'admin', 'editor'])->default('editor');
            $table->timestamps();
        });
        
        Schema::table('user_role', function (Blueprint $table) {
            $table->checkIn('role', ['super-admin', 'admin', 'editor']);
        });
        
        Schema::table('users', function (Blueprint $table) {
          $table->foreign('role_id')->references('id')->on('user_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role');
    }
};
