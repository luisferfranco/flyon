<?php

use App\Models\Tarea;
use App\Models\Accion;
use Livewire\Volt\Component;

new class extends Component {
  public Tarea $tarea;
  public $isEditing = false;

  public $accion;
  public $texto;
  public $acciones = [];

  public function mount() {
    $this->acciones = Accion::where('tarea_id', $this->tarea->id)
      ->orderBy('created_at')
      ->get();
  }

  public function crearAccion() {
    $this->texto = "";
    $this->accion = new Accion();
    $this->isEditing = true;
  }

  public function cancelar() {
    $this->isEditing = false;
  }

  public function editar($id) {
    $this->accion = Accion::find($id);
    $this->texto = $this->accion->descripcion;
    $this->isEditing = true;
  }

  public function guardar() {
    $this->validate([
      'texto' => 'required'
    ]);

    $this->accion->user_id = auth()->id();
    $this->accion->descripcion = $this->texto;
    $this->accion->save();
    $this->texto = "";
    $this->isEditing = false;
    $this->acciones = Accion::where('tarea_id', $this->tarea->id)
      ->orderBy('created_at')
      ->get();
  }
}; ?>

<div>
  {{-- Crear/Editar una nueva Acción --}}
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
          wire:model="texto"
          name="texto"
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
  <section class="mt-4 space-y-4">
    @if ($tarea->acciones->count() > 0)
      @foreach ($tarea->acciones as $accion)
        <div class="card bg-base-200">
          <div class="flex items-center justify-between alert alert-neutral">
            <p>
              Actualizado por <a href="{{ route('user.show', $accion->user->id) }}" class="link link-primary"><span class="font-bold">{{ $accion->user->name }}</span></a> el {{ $accion->created_at->format('Y/m/d') }}
            </p>
            <div>
              <x-button
                wire:click="editar({{ $accion->id }})"
                class="btn btn-info btn-sm"
                icon="icon-[tabler--edit]"
                value="Editar"
              />
            </div>
          </div>
          <div class="card-body">
            <div class="px-6 py-2 markdown">{!! Str::of($accion->descripcion)->markdown() !!}</div>
          </div>
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
