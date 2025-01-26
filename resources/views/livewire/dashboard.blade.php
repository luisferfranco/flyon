<?php

use App\Models\User;
use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
  public $proyecto_id;
  public $tareas;

  public function mount() {
    $this->proyecto_id = null;
    $this->tareas = null;
    $this->cargaTareas();
  }

  #[On('proyecto-seleccionado')]
  public function cambiaProyecto($value) {
    $this->proyecto_id = $value > 0 ? $value : null;
    $this->tareas = null;
    $this->cargaTareas();
  }

  public function cargaTareas($parent_id = null, $nivel = 0) {
    $tareas = Tarea::where('proyecto_id', $this->proyecto_id)
      ->where('tarea_padre_id', $parent_id)
      ->orderBy('id', 'asc')
      ->get();

    foreach ($tareas as $tarea) {
      $tarea->nivel = $nivel;
      $this->tareas[] = $tarea;
      $this->cargaTareas($tarea->id, $nivel + 1);
    }
  }
}; ?>

<div>

  <div class="w-full card">
    <div class="card-body">
      <h1 class="mb-6 text-xl font-extrabold tracking-wide">DASHBOARD</h1>

      <livewire:proyectos.proyecto-select />

      @if ($tareas)
        <x-tabla-tareas :tareas="$tareas" />
      @else
        <div class="flex items-center justify-center h-64 max-w-5xl mx-auto">
          <div class="alert alert-info" role="alert">No hay tareas en este proyecto</div>
        </div>
      @endif

    </div>
  </div>

</div>
