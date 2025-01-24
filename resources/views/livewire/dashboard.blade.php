<?php

use App\Models\User;
use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
  public $isEditing = false;

  public $opcionesEstado=[
    ['id' => 'PEND', 'nombre' => 'Pendiente'],
    ['id' => 'ENPR', 'nombre' => 'En Proceso'],
    ['id' => 'COMP', 'nombre' => 'Completada'],
    ['id' => 'RECH', 'nombre' => 'Rechazada'],
    ['id' => 'CERR', 'nombre' => 'Cerrada'],
  ];
  public $opcionesPrioridad=[
    ['id' => 'URGE', 'nombre' => 'Urgente'],
    ['id' => 'ALTA', 'nombre' => 'Alta'],
    ['id' => 'NORM', 'nombre' => 'Normal'],
    ['id' => 'BAJA', 'nombre' => 'Baja'],
  ];
  public $users;

  public $tareas;

  // Tarea para crear o editar
  public $tarea;
  public $proyecto_id;

  // Formulario
  public $asunto;
  public $descripcion;
  public $estado_id;
  public $prioridad_id;
  public $asignado_id;
  public $fecha_compromiso;
  public $tarea_padre_id = null;

  public function mount() {
    $this->users  = User::orderBy('name')->get();
    $this->cargarTareas();
  }

  #[On('proyecto-seleccionado')]
  public function proyectoSeleccionado($proyecto_id) {
    $this->proyecto_id = $proyecto_id > 0 ? $proyecto_id : null;

    $this->tareas = null;
    $this->cargarTareas();
  }

  public function cargarTareas($parent_id = null, $nivel = 0) {
    $tareas = Tarea::where('proyecto_id', $this->proyecto_id)
      ->where('tarea_padre_id', $parent_id)
      ->orderBy('id', 'asc')
      ->get();

    foreach ($tareas as $tarea) {
      $tarea->nivel = $nivel;
      $this->tareas[] = $tarea;
      $this->cargarTareas($tarea->id, $nivel + 1);
    }
  }

  public function crearTarea($tarea_padre_id = null) {
    $this->tarea            = new Tarea();
    $this->asunto           = null;
    $this->descripcion      = null;
    $this->estado_id        = null;
    $this->prioridad_id     = null;
    $this->asignado_id      = null;
    $this->fecha_compromiso = null;
    $this->tarea_padre_id   = $tarea_padre_id;

    $this->tareas = null;
    $this->cargarTareas();

    $this->isEditing        = true;
  }

  public function guardar() {
    $data = $this->validate([
      'asunto'            => 'required|string|max:255',
      'descripcion'       => 'nullable|string',
      'estado_id'         => 'nullable|string',
      'prioridad_id'      => 'nullable|string',
      'asignado_id'       => 'nullable|exists:users,id',
      'fecha_compromiso'  => 'nullable|date',
    ]);

    $this->tarea->asunto            = $this->asunto;
    $this->tarea->descripcion       = $this->descripcion;
    $this->tarea->estado            = $this->estado_id ?? 'PEND';
    $this->tarea->prioridad         = $this->prioridad_id ?? 'NORM';
    $this->tarea->proyecto_id       = $this->proyecto_id;
    $this->tarea->user_id           = auth()->id();
    $this->tarea->asignado_id       = $this->asignado_id;
    $this->tarea->fecha_compromiso  = $this->fecha_compromiso;
    $this->tarea->tarea_padre_id    = $this->tarea_padre_id;
    $this->tarea->save();

    $this->isEditing = false;
    $this->reset(['asunto', 'descripcion', 'estado_id', 'prioridad_id', 'asignado_id', 'fecha_compromiso']);

    $this->tareas = null;
    $this->cargarTareas();
  }

  public function cancelar() {
    $this->isEditing = false;
    $this->reset(['asunto', 'descripcion', 'estado_id', 'prioridad_id', 'asignado_id', 'fecha_compromiso']);

    $this->tareas = null;
    $this->cargarTareas();
  }

  public function editarTarea($tarea_id) {
    $this->tarea = Tarea::findOrFail($tarea_id);
    $this->asunto = $this->tarea->asunto;
    $this->descripcion = $this->tarea->descripcion;
    $this->estado_id = $this->tarea->estado;
    $this->prioridad_id = $this->tarea->prioridad;
    $this->asignado_id = $this->tarea->asignado_id;
    $this->fecha_compromiso = $this->tarea->fecha_compromiso;
    $this->tarea_padre_id = $this->tarea->tarea_padre_id;

    $this->tareas = null;
    $this->cargarTareas();

    $this->isEditing = true;
  }

  public function borrarTarea($tarea_id) {
    $tarea = Tarea::findOrFail($tarea_id);
    $tarea->delete();

    $this->tareas = null;
    $this->cargarTareas();
  }
}; ?>

<div>

  <div class="w-full card">
    <div class="card-body">
      <h1 class="mb-6 text-xl font-extrabold tracking-wide">DASHBOARD</h1>

      <livewire:proyectos.proyecto-select />

      <div x-data="{ isEditing: $wire.entangle('isEditing') }">
        <div class="mt-4" x-show="!isEditing">
          {{-- Nueva Tarea --}}
          <x-button
            class="btn btn-primary"
            value="Nueva Tarea"
            icon="icon-[tabler--subtask]"
            wire:click="crearTarea"
            />
        </div>

        {{-- Formulario para tareas --}}
        <div x-show="isEditing" x-cloak>
          <h2 class="mt-4 text-lg font-bold tracking-wide">NUEVA TAREA</h2>
          <form x-show="isEditing"
            class="mt-2"
            wire:submit.prevent="guardar"
            x-cloak
            >
            <x-input
              wire:model="asunto"
              name="asunto"
              label="Asunto"
              placeholder="Tarea de la Filial de ..."
              />
            <div class="mt-4">
              <x-textarea
                wire:model="descripcion"
                label="Descripción"
                placeholder="Detalles de la tarea (opcional)"
                rows="5"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
              <x-select
                :options="$opcionesPrioridad"
                wire:model="prioridad_id"
                name="prioridad_id"
                label="Prioridad"
                />
              <x-select
                :options="$opcionesEstado"
                wire:model="estado_id"
                name="estado_id"
                label="Estado"
                />
              <x-select
                :options="$users"
                value="name"
                wire:model="asignado_id"
                name="asignado_id"
                label="Asignada a"
                />
              <x-input
                type="date"
                wire:model="fecha_compromiso"
                name="fecha_compromiso"
                label="Fecha Compromiso"
                placeholder="31/12/2025"
                />
            </div>

            <div class="mt-4">
              <x-button
                type="submit"
                class="btn btn-primary"
                icon="icon-[tabler--check]"
                value="Guardar Tarea"
                wire:click="guardar"
                />
              <x-button
                type="button"
                class="btn btn-primary"
                icon="icon-[tabler--x]"
                value="Cancelar"
                wire:click="cancelar"
                />
            </div>

          </form>
        </div>

        <h2 class="mt-4 text-lg font-bold tracking-wide">TAREAS</h2>

        @if ($tareas)
          <div class="overflow-x-auto h-3/5">
            <table class="table table-pin-rows table-pin-cols">
              <thead>
                <tr>
                  <th></th>
                  <td>Asunto</td>
                  <td>Estado</td>
                  <td>Prioridad</td>
                  <td>Asignado</td>
                  <td>Fecha Compromiso</td>
                  <td></td>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($tareas as $tarea)
                  <tr class="hover">
                    <th>{{ $tarea->id }}</th>

                    {{-- Asunto/Descripción --}}
                      <td class="max-w-96">
                      <div>
                        @for ($i = 0; $i < $tarea->nivel; $i++)
                          &nbsp;&nbsp;&nbsp;&nbsp;
                        @endfor
                        <span class="font-bold text-primary">{{ $tarea->asunto }}</span>
                      </div>
                    </td>

                    {{-- Estado --}}
                    <td>
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
                      <span class="badge {{ $color }}">{{ $tarea->estado }}</span>
                    </td>

                    {{-- Prioridad --}}
                    <td>
                      @php
                        $prioridadColors = [
                          'URGE' => 'badge-error',
                          'ALTA' => 'badge-warning',
                          'NORM' => 'badge-info',
                          'BAJA' => 'badge-secondary',
                        ];
                        $color = $prioridadColors[$tarea->prioridad] ?? 'badge-light';
                      @endphp
                      <span class="badge {{ $color }}">{{ $tarea->prioridad }}</span>
                    </td>

                    {{-- Asignado --}}
                    <td>{{ $tarea->asignado->name ?? null }}</td>

                    {{-- Fecha Compromiso --}}
                    <td>{{ $tarea->fecha_compromiso }}</td>

                    {{-- Acciones --}}
                    <td class="align-right">
                      <x-button
                        class="btn btn-primary btn-xs"
                        icon="icon-[tabler--edit]"
                        wire:click="editarTarea({{ $tarea->id }})"
                        />
                      <x-button
                        class="btn btn-primary btn-xs"
                        icon="icon-[tabler--trash]"
                        wire:click="borrarTarea({{ $tarea->id }})"
                        />
                      <x-button
                        class="btn btn-primary btn-xs"
                        icon="icon-[tabler--subtask]"
                        wire:click="crearTarea({{ $tarea->id }})"
                        />
                    </td>
                    <th>{{ $tarea->id }}</th>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th></th>
                  <td>Asunto</td>
                  <td>Estado</td>
                  <td>Prioridad</td>
                  <td>Asignado</td>
                  <td>Fecha Compromiso</td>
                  <td></td>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        @else
          <div class="mt-4">
            <p>No hay tareas para este proyecto.</p>
          </div>
        @endif
      </div>


    </div>
  </div>

</div>
