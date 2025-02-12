<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
  public User $user;
  public $asignadas;
  public $creadas;
  public $isEditing = false;

  public $name;
  public $email;
  public $nivel;
  public $password;
  public $confirm_password;

  public $niveles = [
    ['id' => '1', 'nombre' => 'Usuario Normal'],
    ['id' => '50', 'nombre' => 'Administrador'],
    ['id' => '99', 'nombre' => 'Super Administrador'],
  ];

  public function mount($user = null)
  {
    if ($user === null) {
      $this->user = auth()->user();
    }

    $this->name       = $this->user->name;
    $this->email      = $this->user->email;
    $this->nivel      = $this->user->rol;
    $this->password   = '';
    $this->confirm_password = '';

    $this->asignadas  = $this->user->tareasAsignadas;
    $this->creadas    = $this->user->tareas;
  }

  public function update()
  {
    $this->validate([
      'name'      => 'required|string',
      'email'     => 'required|email',
      'password'  => 'nullable|min:8|confirmed',
    ]);

    $this->user->update([
      'name'      => $this->name,
      'email'     => $this->email,
      'rol'       => $this->nivel,
      'password'  => $this->password ? bcrypt($this->password) : $this->user->password,
    ]);

    $this->isEditing = false;
  }

  public function cancel() {
    $this->isEditing  = false;
    $this->name       = $this->user->name;
    $this->email      = $this->user->email;
    $this->nivel      = $this->user->rol;
    $this->password   = '';
    $this->confirm_password = '';
  }
}; ?>
<div>
  <div
    class="mb-4 shadow-xl card"
    x-data="{ isEditing: $wire.entangle('isEditing') }"
    >
    {{-- Despliegue de los datos del usuario --}}
    <div
      class="rounded-lg card-body bg-base-100"
      x-show="!isEditing"
      >
      <div class="flex items-end gap-2">
        <x-avatar :user="$user" />
        <div class="flex flex-col">
          <p class="text-2xl font-bold">{{ $user->name }}</p>
          <p class="text-xs text-neutral">{{ $user->email }}</p>
        </div>
      </div>

      <div>
        <x-button
          class="mt-4 shadow btn btn-primary"
          value="Editar"
          @click="isEditing = true"
          />
      </div>

    </div>

    {{-- Formulario de edición de los datos del usuario --}}
    <form
      wire:submit.prevent="update"
      x-show="isEditing"
      x-cloak
      >

      <div class="flex flex-col gap-4 card-body">

        <p class="text-xl">Editando los datos para <span class="font-bold text-info">{{ $user->name }}</span></p>

        <x-input
          label="Nombre"
          type="text"
          wire:model="name"
          name="name"
          placeholder="Juan Camaney"
          required
          />

        <x-input
          label="Correo"
          type="email"
          wire:model="email"
          name="email"
          placeholder="juan.camaney@google.com"
          required
          />

        <x-select
          label="Nivel"
          wire:model="nivel"
          name="nivel"
          :options="$niveles"
          required
          />

        <x-input
          label="Contraseña"
          type="password"
          wire:model="password"
          name="password"
          required
          placeholder="********"
          />

        <x-input
          label="Confirmar Contraseña"
          type="password"
          wire:model="confirm_password"
          name="confirm_password"
          required
          placeholder="********"
          />

        <div class="flex gap-4">
          <x-button
            type="submit"
            class="shadow btn btn-primary"
            value="Guardar"
            icon="icon-[line-md--circle-filled-to-confirm-circle-filled-transition]"
            wire:click="update"
            />
          <x-button
            type="button"
            class="shadow btn btn-error"
            value="Cancelar"
            icon="icon-[line-md--close-circle-filled]"
            wire:click="cancel"
            />
        </div>
      </div>
    </form>

  </div>


  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <x-user.tabla-tareas :tareas="$asignadas" title="Tareas Asignadas" />
    <x-user.tabla-tareas :tareas="$creadas" title="Tareas Creadas" />
  </div>
</div>
