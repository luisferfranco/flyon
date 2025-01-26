<?php

use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Volt\Component;

new class extends Component {
  public $proyecto;
  public $padre;

  public function mount() {
    if (request()->padre !== null) {
      $this->padre    = Tarea::find(request()->padre);
      $this->proyecto = null;
    } else if (request()->proyecto !== null) {
      $this->padre    = null;
      $this->proyecto = Proyecto::find(request()->proyecto);
    } else {
      abort(404);
    }
  }
}; ?>

<div class="w-full card">
  <div class="card-body">
    <h1 class="text-xl tracking-wide">NUEVA TAREA</h1>
    <livewire:tarea.formulario
      :proyecto="$proyecto"
      :padre="$padre" />
  </div>
</div>
