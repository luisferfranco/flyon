<?php

use App\Models\Proyecto;
use Livewire\Volt\Component;

new class extends Component {
  public $proyectos;

  public function mount(){
    $this->proyectos = Proyecto::orderBy('id')
      ->get();
  }
}; ?>

<div>
  <div class="card">
    <div class="card-body">
      <div class="mb-4 border-b border-neutral card-title">Proyectos</div>
      <section class="grid grid-cols-3 gap-4">
        @foreach ($proyectos as $proyecto)
          <div class="w-full shadow-xl card bg-base-200">
            <div class="card-body">
              <div class="h-16 mb-2 overflow-hidden card-title text-ellipsis">
                {{ $proyecto->nombre }}
              </div>
              <div class="overflow-hidden text-sm h-36 max-h-36 text-base-content/75 text-ellipsis">
                {{ $proyecto->descripcion }}
              </div>

              <div class="stats">
                <div class="stat">
                  <div class="text-center stat-tile">ALTAS</div>
                  <div class="text-right stat-value">{{ $proyecto->tareas->where('prioridad', 'alta')->count() }}</div>
                </div>
                <div class="stat">
                  <div class="text-center stat-tile">OTRAS</div>
                  <div class="text-right stat-value">{{ $proyecto->tareas->where('prioridad', '<>', 'alta')->count() }}</div>
                </div>
              </div>

              <p class="mt-6">
                <a href="{{ route('proyecto.show', $proyecto->id) }}"
                  class="btn btn-primary"
                  >
                  Ver detalles
                </a>
              </p>
            </div>
          </div>
        @endforeach
      </section>
    </div>


  </div>
</div>
