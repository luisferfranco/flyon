<?php

use App\Models\Proyecto;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

new class extends Component {
  public $isEditing = false;

  // Todos los proyectos
  public $proyectos;

  public $proyecto;     // Proyecto para crear o editar
  public $proyecto_id;  // Proyecto seleccionado

  // Formulario
  public $nombre;
  public $descripcion;
  public $stub;
  public $nombreBoton;

  public function mount() {
    $this->proyectos    = Proyecto::orderBy('id')->get();
    $this->proyecto_id  = null;
  }

  public function crearProyecto() {
    $this->proyecto     = new Proyecto();
    $this->nombre       = null;
    $this->descripcion  = null;
    $this->stub         = null;
    $this->isEditing    = true;
    $this->nombreBoton  = 'Crear';
  }

  public function editarProyecto() {
    if (!$this->proyecto_id) {
      return;
    }
    $this->proyecto = Proyecto::find($this->proyecto_id);
    $this->nombre = $this->proyecto->nombre;
    $this->descripcion = $this->proyecto->descripcion;
    $this->stub = $this->proyecto->stub;
    $this->isEditing = true;
    $this->nombreBoton  = 'Actualizar';
  }

  public function guardar() {
    $this->validate([
      'nombre' => 'required|string|max:255',
      'descripcion' => 'nullable|string',
      'stub' => 'nullable|string|max:255|unique:proyectos,stub,' . ($this->proyecto->id ?? 'NULL'),
    ]);

    $this->proyecto->nombre       = $this->nombre;
    $this->proyecto->descripcion  = $this->descripcion;
    $this->proyecto->stub         = $this->stub;
    $this->proyecto->admin_id     = auth()->id();
    $this->proyecto->save();

    $this->isEditing    = false;
    $this->proyectos    = Proyecto::orderBy('id')->get();
    $this->proyecto_id  = $this->proyecto->id;
  }

  public function updatedProyectoId($value) {
    $this->dispatch('proyecto-seleccionado', $value);
  }

}; ?>

<div x-data="{ isEditing: $wire.entangle('isEditing') }">

  {{-- Selector de Proyecto y Botones Crear/Editar --}}
  <div
    class="flex space-x-2"
    x-show="!isEditing"
    >
    <x-select
      :options="$proyectos"
      wire:model.live='proyecto_id'
      label="Selecciona el Proyecto"
      />
    <x-button
      class="btn btn-primary"
      icon="icon-[tabler--circle-plus]"
      wire:click='crearProyecto'
      />
    <x-button
      class="btn btn-primary"
      icon="icon-[tabler--edit]"
      wire:click='editarProyecto'
      />
  </div>

  {{-- Formulario de Crear/Editar Proyecto --}}
  <div
    x-show="isEditing"
    >
    <form
      wire:submit.prevent='guardar'
      >
      <x-input
        wire:model='nombre'
        name="nombre"
        label='Nombre del Proyecto'
        placeholder="Proyecto de la Filial de ..."
        />
      <div class="mt-4">
        <x-input
          wire:model='stub'
          name="stub"
          label='Stub'
          placeholder="Identificador único (opcional)"
          />
      </div>
      <div class="mt-4">
        <x-textarea
          wire:model='descripcion'
          name="descripcion"
          label='Descripción'
          placeholder="Descripción del Proyecto"
          />
      </div>

      <div class="mt-4">
        <x-button
          class="btn btn-primary"
          icon="icon-[tabler--check]"
          :value="$nombreBoton"
          />
        <x-button
          type="button"
          class="btn btn-primary"
          icon="icon-[tabler--x]"
          @click='isEditing = false'
          value="Cancelar"
          />
      </div>
    </form>
  </div>

</div>
