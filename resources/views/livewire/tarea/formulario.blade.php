<?php

use App\Models\User;
use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Volt\Component;

new class extends Component {
  // Campos del formulario
  public $proyecto_id;
  public $asunto;
  public $descripcion;
  public $prioridad_id;
  public $estado_id;
  public $asignado_id;
  public $fecha_compromiso;
  public $tarea_padre_id;

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
  public $url_previa;

  public function mount(Proyecto $proyecto, Tarea $tarea = null) {
    $this->proyecto = $proyecto;
    $this->url_previa = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();

    if ($tarea != new Tarea()) {
      $this->tarea            = $tarea;
      $this->proyecto_id      = $this->tarea->proyecto_id;
      $this->asunto           = $this->tarea->asunto;
      $this->descripcion      = $this->tarea->descripcion;
      $this->estado_id        = $this->tarea->estado;
      $this->prioridad_id     = $this->tarea->prioridad;
      $this->asignado_id      = $this->tarea->asignado_id;
      $this->fecha_compromiso = $this->tarea->fecha_compromiso;
    } else {
      $this->tarea = new Tarea();
      $this->proyecto_id = $proyecto->id;
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
    $this->tarea->user_id           = auth()->id();
    $this->tarea->save();

    // Actualizar proyecto_id de las subtareas de forma recursiva
    $this->actualizarProyectoId($this->tarea, $this->proyecto_id);
    $this->redirectRoute($this->url_previa, ['proyecto' => $this->proyecto], navigate: true);
  }

  public function actualizarProyectoId($tarea, $nuevoProyectoId) {
    $tarea->update(['proyecto_id' => $nuevoProyectoId]);

    $subtareas = Tarea::where('tarea_padre_id', $tarea->id)->get();
    foreach ($subtareas as $subtarea) {
      $this->actualizarProyectoId($subtarea, $nuevoProyectoId);
    }
  }

  public function cancelar() {
    $this->redirectRoute($this->url_previa, ['proyecto' => $this->proyecto], navigate: true);
  }

}; ?>

<form
  class="mt-2"
  wire:submit.prevent="guardar"
  >
  <div class="flex flex-col mt-4 space-y-4">
    <x-select
      :options="$proyectos"
      value="nombre"
      wire:model.live="proyecto_id"
      name="proyecto_id"
      label="Asignada al Proyecto"
      />

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
  </div>
</form>
