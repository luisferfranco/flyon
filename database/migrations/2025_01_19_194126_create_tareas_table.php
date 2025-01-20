<?php

use App\Models\Proyecto;
use App\Models\User;
use App\Models\Tarea;
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
    Schema::create('tareas', function (Blueprint $table) {
      $table->id();

      $table->string('asunto');
      $table->text('descripcion')->nullable();
      $table->string('estado')->default('pendiente');
      $table->string('prioridad')->default('normal');
      $table->foreignIdFor(Proyecto::class, 'proyecto_id')
        ->nullable()
        ->constrained()
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->foreignIdFor(User::class, 'user_id')
        ->constrained()
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->foreignIdFor(User::class, 'asignado_id')
        ->nullable()
        ->constrained('users')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->date('fecha_compromiso')->nullable();
      $table->foreignIdFor(Tarea::class, 'tarea_padre_id')
        ->nullable()
        ->constrained('tareas')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tareas');
  }
};
