<?php

use App\Models\Tarea;
use Livewire\Volt\Component;

new class extends Component {
  public Tarea $tarea;
  public $tareas = [];

  public $isEditing = false;
  public $accion;

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

  public function mount(Tarea $tarea)
  {
    $this->tarea = $tarea;
    $this->tareas = $this->obtenerTareasRecursivas($this->tarea->id);
  }

  private function obtenerTareasRecursivas($tareaId, $nivel = 1)
  {
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

  public function crearAccion() {
    $this->isEditing = true;
  }

  public function guardar()
  {
    $this->validate([
      'accion' => 'required'
    ]);

    $this->tarea->acciones()->create([
      'descripcion' => $this->accion,
      'user_id'     => auth()->id()
    ]);

    $this->accion     = '';
    $this->isEditing  = false;

    $this->tarea->refresh();
  }

  public function cancelarAccion()
  {
    $this->accion = '';
    $this->isEditing = false;
  }
}; ?>

<div>
  <w-full class="shadow-xl card">
    <div class="card-body">
      <h1 class="mb-6 text-xl tracking-wide">TAREA #{{ $tarea->id }}</h1>
      <h1 class="text-2xl font-extrabold tracking-wide">{{ $tarea->asunto }}</h1>

      <div class="mb-4 w-96">
        <div class="grid grid-cols-2 gap-2">
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

          <p>Proyecto:</p>
          <div class="flex items-center space-x-1 font-bold">
            @if ($tarea->proyecto)
              {{ $tarea->proyecto->nombre }}
            @else
              <span class="icon-[tabler--alert-triangle-filled] text-warning"></span>
              <span>Sin Proyecto</span>
            @endif
          </div>

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

      @if ($tareas)
        <h2 class="mt-6 text-xl font-bold">Subtareas</h2>
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
                @foreach ($tareas as $t)
                  <tr class="hover">
                    <th>{{ $t->id }}</th>

                    {{-- Asunto/Descripción --}}
                    <td class="max-w-96">
                      <div>
                        @for ($i = 0; $i < $t->nivel; $i++)
                          &nbsp;&nbsp;&nbsp;&nbsp;
                        @endfor
                        <span class="font-bold text-primary">{{ $t->asunto }}</span>
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
                        $color = $estadoColors[$t->estado] ?? 'badge-light';
                      @endphp
                      <span class="badge {{ $color }}">{{ $t->estado }}</span>
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
                        $color = $prioridadColors[$t->prioridad] ?? 'badge-light';
                      @endphp
                      <span class="badge {{ $color }}">{{ $t->prioridad }}</span>
                    </td>

                    {{-- Asignado --}}
                    <td>{{ $t->asignado->name ?? null }}</td>

                    {{-- Fecha Compromiso --}}
                    <td>{{ $t->fecha_compromiso }}</td>

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
      @endif

      {{-- Acciones --}}
      <div x-data="{ isEditing: $wire.entangle('isEditing') }" class="my-6">
        <div x-show="!isEditing">
          <x-button value="Nueva Acción"
            class="btn btn-primary"
            icon="icon-[tabler--circle-plus-filled]"
            wire:click="crearAccion"
            />
        </div>

        <div x-show="isEditing">
          <form x-show="isEditing"
            class="mt-2"
            wire:submit.prevent="guardar"
            x-cloak
            >
            <x-textarea
              label="Acción"
              wire:model="accion"
              name="accion"
              rows="10"
              />
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
      </div>

      @if ($tarea->acciones->count() > 0)
        @foreach ($tarea->acciones as $accion)
          <div class="mb-4 overflow-hidden border shadow-md rounded-xl border-neutral bg-base-200">
            <div class="px-6 py-2 bg-base-300">
              Actualizado por {{ $accion->user->name }} el {{ $accion->created_at->format('Y/m/d') }}
            </div>
            <div class="px-6 py-2 markdown">{!! Str::of($accion->descripcion)->markdown() !!}</div>
          </div>
        @endforeach
      @else
        <div class="flex items-center space-x-1">
          <span class="text-warning icon-[tabler--alert-triangle-filled]"></span>
          <span>No hay acciones registradas.</span>
        </div>
      @endif


    </div>
  </w-full>
</div>
