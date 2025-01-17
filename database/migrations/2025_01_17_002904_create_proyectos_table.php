<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('proyectos', function (Blueprint $table) {
      $table->id();

      $table->string('nombre');
      $table->text('descripcion')->nullable();
      $table->string('stub')->unique()->nullable();
      $table->foreignIdFor(User::class, 'admin_id')
        ->constrained()
        ->onUpdate('cascade')
        ->onDelete('cascade');
      $table->softDeletes();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('proyectos');
  }
};
