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
      <section class="space-y-4">
        @foreach ($proyectos as $proyecto)
          <div>
            <p class="text-xl font-bold">
              <a href="{{ route('proyecto.show', $proyecto->id) }}"
                class="link link-primary"
                >
                {{ $proyecto->nombre }}
              </a>
            </p>
            <p class="text-sm text-neutral">{{ $proyecto->descripcion }}</p>
          </div>
        @endforeach
      </section>
    </div>


  </div>
</div>
