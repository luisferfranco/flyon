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
  public $deleteWarning = false;

  public function mount(){
    $this->users = User::orderBy('name')->get();
    $this->user = new User();
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

  public function delete($id) {
    $this->user = User::find($id);
    $this->deleteWarning = true;
  }

  public function executeDelete() {
    $this->user->tareasAsignadas()->update(['asignado_id' => null]);
    $this->user->tareas()->delete();
    $this->user->delete();
    $this->users = User::orderBy('name')->get();
    $this->deleteWarning = false;
  }
}; ?>

<div class="shadow-xl card">

  <div class="card-body">
    <div class="mb-6 card-title">
      <h1 class="text-3xl font-bold tracking-wide uppercase text-info">Usuarios</h1>
    </div>

    <div x-data="{
      isEditing: $wire.entangle('isEditing'),
      deleteWarning: $wire.entangle('deleteWarning'),
      }">
      <div class="mb-6" x-show="!isEditing && !deleteWarning">
        <x-button
          value="Nuevo Usuario"
          icon="icon-[line-md--account-add] size-8"
          class="shadow btn btn-primary"
          wire:click="create"
          />
      </div>

      {{-- Formulario de creación/edición --}}
      <form
        x-show="isEditing"
        x-cloak
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

      {{-- Advertencia de eliminación --}}
      <div
        x-show="deleteWarning"
        x-cloak
        class="border shadow-xl card bg-base-200"
        >
        <div class="alert alert-error" role="alert">
          <strong>¡Atención!</strong> ¿Estás seguro de eliminar el usuario <strong>{{ $user->name }}</strong>?
        </div>
        @if ($user->tareasAsignadas->count() > 0)
          <div class="flex items-center gap-4 alert alert-warning">
            <span class="icon-[line-md--hazard-lights-filled-loop] size-6"></span>
            <p><strong>¡Atención!</strong> El usuario tiene {{ $user->tareasAsignadas->count() }} tareas asignadas, si eliminas el usuario, dichas tareas quedarán sin asignar</p>
          </div>
        @endif

        @if ($user->tareas->count() > 0)
          <div class="flex items-center gap-4 alert alert-warning">
            <span class="icon-[line-md--hazard-lights-filled-loop] size-6"></span>
            <p><strong>¡Atención!</strong> El usuario tiene {{ $user->tareas->count() }} tareas creadas, si eliminas el usuario, dichas tareas también serán borradas, ya que no pueden existir tareas sin creador. Así mismo, todas las subtareas serán borradas</p>
          </div>
        @endif
        <div class="card-body">
          <div class="flex space-x-2">
            <x-button
              value="Eliminar"
              icon="icon-[ion--trash-bin-sharp] size-6"
              class="btn btn-error"
              wire:click="executeDelete"
              />
            <x-button
              value="Cancelar"
              icon="icon-[ion--close-circle-sharp] size-6"
              class="btn btn-secondary"
              @click="deleteWarning = false"
              />
          </div>
        </div>
      </div>

      <div
        class="h-56 overflow-x-auto"
        x-show="!isEditing && !deleteWarning"
        x-cloak
        >
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
                    value="Eliminar"
                    icon="icon-[line-md--account-remove] size-4"
                    class="btn btn-error btn-sm"
                    wire:click="delete({{ $u->id }})"
                    />
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
