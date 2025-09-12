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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // usuário autenticado
            $table->string('action'); // create, update, delete, select
            $table->string('table_name'); // nome da tabela afetada
            $table->unsignedBigInteger('record_id')->nullable(); // id do registro (se aplicável)
            $table->json('old_data')->nullable(); // valores antigos
            $table->json('new_data')->nullable(); // valores novos
            $table->ipAddress('ip_address')->nullable(); // ip do usuário
            $table->text('user_agent')->nullable(); // navegador/dispositivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
