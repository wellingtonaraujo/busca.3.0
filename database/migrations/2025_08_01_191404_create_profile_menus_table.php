<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profile_menus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('profile_id')
                  ->constrained('profiles')
                  ->onDelete('cascade');

            $table->foreignId('menu_id')
                  ->constrained('menus')
                  ->onDelete('cascade');

            $table->boolean('can_view')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_menus');
    }
};

