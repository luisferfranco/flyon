<?php

use App\Models\Tarea;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
  public Tarea $tarea;
  public $tareas = [];

  public $isEditing = false;
  public $confirm = false;

  public $estados=[
    'PEND' => 'Pendiente',
    'ENPR' => 'En Proceso',
    'COMP' => 'Completada',
    'RECH' => 'Rechazada',
    'CERR' => 'Cerrada',
  ];
  public $prioridades=[
    'URGE' => 'Urgente',
    'ALTA' => 'Alta',
    'NORM' => 'Normal',
    'BAJA' => 'Baja',
  ];

  public function mount(Tarea $tarea) {
    $this->isEditing = request()->is('tarea/*/edit');

    $this->tarea = $tarea;
    $this->tareas = $this->obtenerTareasRecursivas($this->tarea->id);
  }

  private function obtenerTareasRecursivas($tareaId, $nivel = 0) {
    $tareasHijas = Tarea::where('tarea_padre_id', $tareaId)->get();
    $resultado = [];

    foreach ($tareasHijas as $tarea) {
      $tarea->nivel = $nivel;
      $resultado[] = $tarea;
      $tareasNietas = $this->obtenerTareasRecursivas($tarea->id, $nivel + 1);
      $resultado = array_merge($resultado, $tareasNietas);
    }

    return $resultado;
  }

  public function eliminar() {
    $this->tarea->delete();
    return redirect()->route('dashboard');
  }
}; ?>

<div class="w-full shadow-xl card">
  <div class="card-body">
    <div x-data="{
      isEditing: $wire.entangle('isEditing'),
      confirm: $wire.entangle('confirm'),
      }"
      >

      {{-- Sección para confirmar eliminación --}}
      <section x-show="confirm" x-cloak>
        <div class="w-full mb-6 border shadow-xl card bg-base-200 glass border-neutral">
          <div class="card-body">
            <p class="mb-2 card-title">¿Estás seguro de querer eliminar esta tarea?</p>
            <div class="flex items-center gap-4 mb-4 alert alert-warning" role="alert">
              <span class="icon-[tabler--alert-triangle] size-6"></span>
              <p><span class="text-lg font-semibold">Alerta:</span> Esta acción es irreversible y también borrará todas las tareas dependientes de esta tarea</p>
            </div>
            <div class="flex space-x-2">
              <x-button
                class="btn btn-error"
                wire:click="eliminar"
                value="Eliminar"
                icon="icon-[tabler--trash]"
                />
              <x-button
                class="btn btn-secondary"
                @click="confirm = false"
                value="Cancelar"
                icon="icon-[tabler--x]"
                />
            </div>
          </div>
        </div>
      </section>

      <section x-show="isEditing">
        <h1 class="text-xl tracking-wide">TAREA #{{ $tarea->id }}</h1>
        <livewire:tarea.formulario :tarea="$tarea" />
      </section>

      {{-- Información general de la tarea --}}
      <section x-show="!isEditing" x-cloak>
        <div class="flex justify-between">
          <h1 class="text-xl tracking-wide">TAREA #{{ $tarea->id }}</h1>
          <div class="flex items-center space-x-2" x-show="!confirm">
            <a
              value="Editar"
              class="mb-6 btn btn-primary"
              href="{{ route('tarea.edit', $tarea) }}"
              wire:navigate
              >
              <span class="icon-[tabler--pencil]"></span>
              Editar
            </a>
            <a
              value="Eliminar"
              class="mb-6 btn btn-error"
              @click="confirm = true"
              >
              <span class="icon-[tabler--trash]"></span>
              Eliminar
            </a>
          </div>
        </div>

        <h1 class="text-2xl font-extrabold tracking-wide">{{ $tarea->asunto }}</h1>
        <div class="mb-4 w-96">
          <div class="grid grid-cols-2 gap-2">
            <p>Proyecto:</p>
            <div class="flex items-center space-x-1 font-bold">
              @if ($tarea->proyecto)
                {{ $tarea->proyecto->nombre }}
              @else
                <span class="icon-[tabler--alert-triangle-filled] text-warning"></span>
                <span>Sin Proyecto</span>
              @endif
            </div>

            <p>Creada por</p>
            <p>{{ $tarea->user->name }}</p>

            <p>Fecha de Creación:</p>
            <p>{{ $tarea->created_at->format('Y/m/d') }}.</p>

            <p>Última modificación:</p>
            <p>{{ $tarea->updated_at->format('Y/m/d') }}</p>

            @if ($tarea->fecha_compromiso)
              <p>Fecha de Compromiso:</p>
              <p>{{ $tarea->fecha_compromiso->format('Y/m/d') }}</p>
            @endif

            @if ($tarea->asignado_id)
              <p>Asignado a:</p>
              <p>{{ $tarea->asignado->name }}</p>
            @endif

            <p>Estado:</p>
            @php
              $estadoColors = [
                'PEND' => 'badge-warning',
                'ENPR' => 'badge-info',
                'COMP' => 'badge-success',
                'RECH' => 'badge-danger',
                'CERR' => 'badge-secondary',
              ];
              $color = $estadoColors[$tarea->estado] ?? 'badge-light';
            @endphp
            <p class="badge {{ $color }}">{{ $estados[$tarea->estado] }}</p>

            <p>Prioridad:</p>
            @php
              $prioridadColors = [
                'URGE' => 'badge-danger',
                'ALTA' => 'badge-warning',
                'NORM' => 'badge-info',
                'BAJA' => 'badge-light',
              ];
              $color = $prioridadColors[$tarea->prioridad] ?? 'badge-light';
            @endphp
            <p class="badge {{ $color }}">{{ $prioridades[$tarea->prioridad] }}</p>
          </div>
        </div>
        @if ($tarea->descripcion)
          <hr>
          <p class="py-4 text-neutral">{{ $tarea->descripcion }}</p>
          <hr>
        @endif
      </section>

      {{-- Despliegue de subtareas --}}
      @if ($tareas)
        <h2 class="mt-6 text-xl font-bold">Subtareas</h2>
        <x-tabla-tareas :tareas="$tareas" />
      @endif

      {{-- Crear y mostrar Acciones --}}
      <div class="mt-6">
        <livewire:accion.index :tarea="$tarea" />
      </div>
    </div>

  </div>
</div>
