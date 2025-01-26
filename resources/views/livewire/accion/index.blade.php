<?php

use App\Models\Tarea;
use App\Models\Accion;
use Livewire\Volt\Component;

new class extends Component {
  public Tarea $tarea;
  public $isEditing = false;

  public $accion;
  public $acciones = [];

  public function mount() {
    $this->acciones = Accion::where('tarea_id', $this->tarea->id)
      ->orderBy('created_at')
      ->get();
  }

  public function crearAccion() {
    $this->accion = "";
    $this->isEditing = true;
    $this->acciones = Accion::where('tarea_id', $this->tarea->id)
      ->orderBy('created_at')
      ->get();
  }

  public function cancelar() {
    $this->isEditing = false;
  }

  public function guardar() {
    $this->validate([
      'accion' => 'required'
    ]);

    $this->tarea->acciones()->create([
      'descripcion' => $this->accion,
      'user_id'     => auth()->id()
    ]);

    $this->accion = "";
    $this->isEditing = false;
  }
}; ?>

<div>
  {{-- Crear una nueva tarea --}}
  <section x-data="{ isEditing: $wire.entangle('isEditing') }">
    <x-button
      value="Nueva Acción"
      class="btn btn-primary"
      icon="icon-[tabler--circle-plus-filled]"
      wire:click="crearAccion"
      x-show="!isEditing"
      />

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

  </section>

  {{-- Listado de acciones --}}
  <section class="mt-4">
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
  </section>
</div>
