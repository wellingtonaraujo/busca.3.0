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
            $table->string('name')->unique();
            $table->string('cpf',11);
            $table->date('nascimento');
            $table->string('logradouro');
            $table->string('numero');
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cidade');
            $table->string('uf',2);
            $table->string('cep',8);
            $table->string('celular');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('user_status_id')->unsigned()->defaut(1);
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
