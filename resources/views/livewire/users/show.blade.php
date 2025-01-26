<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
  public User $user;
  public $asignadas;
  public $creadas;

  public function mount($user = null)
  {
    if ($user === null) {
      $this->user = auth()->user();
    }

    $this->asignadas  = $this->user->tareasAsignadas;
    $this->creadas    = $this->user->tareas;
  }
}; ?>

<div>
  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <x-user.tabla-tareas :tareas="$asignadas" title="Tareas Asignadas" />
    <x-user.tabla-tareas :tareas="$creadas" title="Tareas Creadas" />
  </div>
</div>
