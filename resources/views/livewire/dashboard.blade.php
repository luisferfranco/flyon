<?php

use App\Models\Proyecto;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
  #[On('proyecto-seleccionado')]
  public function proyectoSeleccionado($proyecto_id) {
    info('dashboard:proyectoSeleccionado', ['proyecto_id' => $proyecto_id]);
  }
}; ?>

<div>

  <div class="w-full card">
    <div class="card-body">
      <h1 class="mb-6 text-xl font-extrabold tracking-wide">DASHBOARD</h1>

      <livewire:proyectos.proyecto-select />

    </div>
  </div>

</div>
