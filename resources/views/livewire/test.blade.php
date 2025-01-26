<?php

use App\Models\Tarea;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
  public $proyecto_id;
  public $tareas;
  public $isEditing = false;

  public function mount() {
    $this->isEditing = request()->is('test/*/edit');

    $this->tareas = Tarea::where('proyecto_id', $this->proyecto_id)->get();
  }

  #[On('proyecto-seleccionado')]
  public function cambiaProyecto($value) {
    $this->proyecto_id = $value > 0 ? $value : null;
    $this->tareas = Tarea::where('proyecto_id', $this->proyecto_id)->get();
  }
}; ?>

<div>
    <livewire:proyectos.proyecto-select />
    <x-tabla-tareas :tareas="$tareas" />
    @if ($isEditing)
      <h1 class="text-xl">Editando</h1>
    @else
      <h1 class="text-xl">No editando</h1>
    @endif
</div>
