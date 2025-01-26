<?php

use App\Models\User;
use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Volt\Component;

new class extends Component {
  // Campos del formulario
  public $proyecto_id       = null;
  public $asunto            = null;
  public $descripcion       = null;
  public $prioridad_id      = null;
  public $estado_id         = null;
  public $asignado_id       = null;
  public $fecha_compromiso  = null;
  public $tarea_padre_id    = null;

  // Valores de los select
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
  public $proyectos;
  public $proyecto;
  public $tarea;
  public $padre;
  public $url_previa;

  public function mount($proyecto = null, $tarea = null, $padre = null) {
    $this->proyecto = $proyecto;
    $this->url_previa = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();

    if ($tarea !== null) {
      $this->tarea            = $tarea;
      $this->proyecto_id      = $this->tarea->proyecto_id;
      $this->asunto           = $this->tarea->asunto;
      $this->descripcion      = $this->tarea->descripcion;
      $this->estado_id        = $this->tarea->estado;
      $this->prioridad_id     = $this->tarea->prioridad;
      $this->asignado_id      = $this->tarea->asignado_id;
      $this->fecha_compromiso = $this->tarea->fecha_compromiso;
      $this->tarea_padre_id   = $this->tarea->tarea_padre_id;
    } else if ($padre !== null) {
      $this->tarea          = new Tarea();
      $this->proyecto_id    = $padre->proyecto_id;
      $this->tarea_padre_id = $padre->id;
    } else if ($proyecto !== null) {
      $this->tarea          = new Tarea();
      $this->proyecto_id    = $proyecto->id;
      $this->tarea_padre_id = null;
    }

    $this->users            = User::orderBy('name')->get();
    $this->proyectos        = Proyecto::orderBy('nombre')->get();
  }

  public function guardar() {
    $this->validate([
      'asunto'            => 'required|string|max:255',
      'descripcion'       => 'nullable|string',
      'prioridad_id'      => 'nullable|in:URGE,ALTA,NORM,BAJA',
      'estado_id'         => 'nullable|in:PEND,ENPR,COMP,RECH,CERR',
      'asignado_id'       => 'nullable|exists:users,id',
      'fecha_compromiso'  => 'nullable|date',
    ]);

    $this->tarea->proyecto_id       = $this->proyecto_id;
    $this->tarea->asunto            = $this->asunto;
    $this->tarea->descripcion       = $this->descripcion;
    $this->tarea->prioridad         = $this->prioridad_id ?? 'NORM';
    $this->tarea->estado            = $this->estado_id ?? 'PEND';
    $this->tarea->asignado_id       = $this->asignado_id;
    $this->tarea->fecha_compromiso  = $this->fecha_compromiso;
    $this->tarea->tarea_padre_id    = $this->tarea_padre_id;
    $this->tarea->user_id           = auth()->id();
    $this->tarea->save();

    // Actualizar proyecto_id de las subtareas de forma recursiva
    $this->actualizarProyectoId($this->tarea, $this->proyecto_id);

    if ($this->tarea == new Tarea()) {
      // Si la tarea es nueva, viene de un proyecto, hay que redirigir a la vista de proyecto
      $this->redirectRoute($this->url_previa, ['proyecto' => $this->proyecto], navigate: true);
    } else {
      // Si la tarea existe, es una edición de una tarea, hay que redirigir a la vista de la tarea
      $this->redirectRoute($this->url_previa, ['tarea' => $this->tarea], navigate: true);
    }


  }

  public function actualizarProyectoId($tarea, $nuevoProyectoId) {
    $tarea->update(['proyecto_id' => $nuevoProyectoId]);

    $subtareas = Tarea::where('tarea_padre_id', $tarea->id)->get();
    foreach ($subtareas as $subtarea) {
      $this->actualizarProyectoId($subtarea, $nuevoProyectoId);
    }
  }

  public function cancelar() {
    info('formulario', ['proyecto' => $this->proyecto, 'tarea' => $this->tarea]);

    if ($this->proyecto !== null) {
      $this->redirectRoute($this->url_previa, ['proyecto' => $this->proyecto], navigate: true);
    } else {
      $this->redirectRoute($this->url_previa, ['tarea' => $this->tarea], navigate: true);
    }
  }

}; ?>

<form
  class="mt-2"
  wire:submit.prevent="guardar"
  >
  <div class="flex flex-col mt-4 space-y-4">
    @if ($padre)
      <p class="mb-6">Tarea Padre: <span class="font-bold text-accent">{{ $padre->asunto }}</span></p>
    @else
      <x-select
        :options="$proyectos"
        value="nombre"
        wire:model.live="proyecto_id"
        name="proyecto_id"
        label="Asignada al Proyecto"
        />
    @endif

    <x-input
      wire:model="asunto"
      name="asunto"
      label="Asunto"
      placeholder="Tarea de la Filial de ..."
      />

    <x-textarea
      wire:model="descripcion"
      label="Descripción"
      placeholder="Detalles de la tarea (opcional)"
      rows="5"
      />

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <x-select
        :options="$opcionesPrioridad"
        nullable
        wire:model="prioridad_id"
        name="prioridad_id"
        label="Prioridad"
        />
      <x-select
        :options="$opcionesEstado"
        wire:model="estado_id"
        nullable
        name="estado_id"
        label="Estado"
        />
      <x-select
        :options="$users"
        value="name"
        wire:model="asignado_id"
        nullable
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

    <div class="flex items-center space-x-2">
      <x-button
        type="submit"
        class="btn btn-primary"
        icon="icon-[tabler--check]"
        value="Guardar Tarea"
        wire:click="guardar"
        />
      <x-button
        type="button"
        wire:click="cancelar"
        class="btn btn-error"
        icon="icon-[tabler--x]"
        value="Cancelar"
        />
    </div>
  </p>
</form>
