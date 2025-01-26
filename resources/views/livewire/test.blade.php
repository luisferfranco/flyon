<?php

use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
  public Proyecto $proyecto;
  public Tarea $padre;
}; ?>

<div>
  <div>Proyecto {{ $proyecto }}</div>
  <div>Tarea {{ $padre }}</div>
</div>
