<?php

use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Volt\Component;

new class extends Component {
  public Tarea $tarea;
  public Proyecto $proyecto;

  public function mount(Proyecto $proyecto) {
    info('Crear Tarea', ['proyecto' => $proyecto]);
    $this->proyecto = $proyecto;
    $this->tarea = new Tarea();
  }

  #[On('tarea-actualizada')]
  public function tareaActualizada() {
    $this->tarea->refresh();
    $this->redirect(route('tarea.dashboard'));
  }
}; ?>

<div class="w-full card">
  <div class="card-body">
    <h1 class="text-xl tracking-wide">NUEVA TAREA</h1>
    <livewire:tarea.formulario :proyecto="$proyecto" />
  </div>
</div>
