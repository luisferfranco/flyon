<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
  public $users;
  public $estados;

  public $user;
  public $name;
  public $email;
  public $password;
  public $isEditing = false;

  public function mount(){
    $this->users = User::orderBy('name')->get();
    $this->estados = config('estados');
  }

  public function create() {
    $this->user       = new User();
    $this->name       = null;
    $this->email      = null;
    $this->password   = null;
    $this->isEditing  = true;
  }

  public function save() {
    $this->validate([
      'name'  => 'required',
      'email' => 'required|email',
    ]);

    $this->user->name  = $this->name;
    $this->user->email = $this->email;
    $this->user->password = $this->password ?? bcrypt('password');
    $this->user->save();

    $this->users = User::orderBy('name')->get();
    $this->isEditing = false;
  }

  public function edit($id) {
    $this->user       = User::find($id);
    $this->name       = $this->user->name;
    $this->email      = $this->user->email;
    $this->password   = $this->user->password;
    $this->isEditing  = true;
  }
}; ?>

<div class="shadow-xl card">

  <div class="card-body">
    <div class="mb-6 card-title">
      <h1 class="text-3xl font-bold tracking-wide uppercase text-info">Usuarios</h1>
    </div>

    <div x-data="{ isEditing: $wire.entangle('isEditing')}">
      <div class="mb-6" x-show="!isEditing">
        <x-button
          value="Nuevo Usuario"
          icon="icon-[fluent-color--add-circle-16] size-8"
          class="shadow btn btn-primary"
          wire:click="create"
          />
      </div>

      {{-- Formulario de creación/edición --}}
      <form
        x-show="isEditing"
        wire:submit.prevent='save'
        class="mb-6 border shadow-xl card bg-base-200"
        >

        <div class="alert alert-info" role="alert">
          <strong>¡Atención!</strong> Los usuarios recién creados tendrán como password "password", para los usuarios existentes, no se modificará el password actual
        </div>

        <div class="grid grid-cols-1 gap-4 card-body">
          <x-input
            type="text"
            required
            label="Nombre"
            placeholder="Nombre del usuario"
            wire:model="name"
            name="name"
            />
            <x-input
              type="email"
              required
              label="Correo"
              placeholder="Correo del usuario"
              wire:model="email"
              name="email"
              />
            <div class="flex space-x-2">
              <x-button
                type="submit"
                value="Guardar"
                icon="icon-[ion--checkmark-circle-sharp] size-6"
                class="btn btn-primary"
                />
              <x-button
                type="button"
                value="Cancelar"
                icon="icon-[ion--close-circle-sharp] size-6"
                class="btn btn-secondary"
                @click="isEditing = false"
                />
            </div>
        </div>
      </form>
    </div>

    <div class="h-56 overflow-x-auto">
      <table class="table table-sm table-pin-rows table-pin-cols">
        <thead>
          <tr>
            <th class="w-8"></th>
            <td>Nombre</td>
            @foreach ($estados as $e)
              <td class="text-center">
                <span class="badge {{ $e['color'] }}">
                  {{ $e['nombre'] }}
                </span>
              </td>
            @endforeach
            <td>Acciones</td>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $u)
            <tr class="hover">
              <th class="w-8">{{ $u->id }}</th>
              <td>
                <a
                  class="link link-primary"
                  href="{{ route('user.show', $u) }}"
                  wire:navigate
                  >
                  {{ $u->name }}
                </a>
              </td>
              @foreach ($estados as $e)
                <td class="text-center">
                  {{ $u->tareasAsignadas()->where('estado', $e['id'])->count() ?: 0 }}
                </td>
              @endforeach
              <td>
                <x-button
                  value="Editar"
                  icon="icon-[ion--pencil] size-3"
                  class="btn btn-primary btn-xs"
                  wire:click="edit({{ $u->id }})"
                  />
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>
