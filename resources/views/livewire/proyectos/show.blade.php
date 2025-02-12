<?php

use App\Models\User;
use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
  public $proyecto_id;
  public $proyecto;
  public $tareas      = [];

  public function mount($proyecto = null) {
    $this->proyecto_id = $proyecto ? Proyecto::find($proyecto)->id : 1;
    $this->proyecto = Proyecto::find($this->proyecto_id);
    $this->tareas = null;
    $this->cargaTareas();
  }

  #[On('proyecto-seleccionado')]
  public function cambiaProyecto($value) {
    $this->redirectRoute('proyecto.show', ['proyecto' => $value]);
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
    <div class="text-black alert alert-warning">
      <h1 class="text-2xl font-extrabold tracking-wide uppercase">{{ $proyecto->nombre }}</h1>
      <p class="text-sm">{{ $proyecto->descripcion }}</p>
    </div>
    <div class="card-body" x-data="{}">

      <livewire:proyectos.proyecto-select :pid='$proyecto_id' />

      <div>
        <x-a
          class="my-6 btn btn-primary"
          href="{{ route('tarea.create', ['proyecto' => $proyecto_id]) }}"
          value="Crear Tarea"
          icon="icon-[tabler--subtask]"
          />
      </div>

      @if ($tareas)
        <div x-cloak>
          <x-tabla-tareas :tareas="$tareas" />
        </div>
      @else
        <div class="flex items-center justify-center h-64 max-w-5xl mx-auto">
          <div class="alert alert-info" role="alert">No hay tareas en este proyecto</div>
        </div>
      @endif

    </div>
  </div>

</div>
