<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('name', 100);
            $table->string('icon', 100)->nullable();
            $table->string('route', 255)->nullable();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('menus')
                  ->nullOnDelete(); // FOREIGN KEY ON DELETE SET NULL
            $table->integer('order_no')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
