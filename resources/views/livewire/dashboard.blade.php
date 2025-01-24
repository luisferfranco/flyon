<?php

use App\Models\User;
use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

}; ?>

<div>

  <div class="w-full card">
    <div class="card-body">
      <h1 class="mb-6 text-xl font-extrabold tracking-wide">DASHBOARD</h1>

      <livewire:proyectos.proyecto-select />

      <livewire:tarea.tabla proyecto />

    </div>
  </div>

</div>
