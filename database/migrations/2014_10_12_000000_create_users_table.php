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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('profile_id')->unsigned()->nullable();
            $table->integer('user_status_id')->unsigned()->defaut(1);
            $table->integer('entidade_atual_id')->unsigned()->defaut(1);
            $table->integer('pessoa_id')->unsigned();
            $table->string('cpf')->unique();
            $table->string('departamento')->nullable();
            $table->string('funcao')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
