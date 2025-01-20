<?php

use App\Models\User;
use App\Models\Tarea;
use App\Models\Proyecto;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
  public $isEditing = false;

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

  public $tareas;

  // Tarea para crear o editar
  public $tarea;
  public $proyecto_id;

  // Formulario
  public $asunto;
  public $descripcion;
  public $estado_id;
  public $prioridad_id;
  public $asignado_id;
  public $fecha_compromiso;
  public $tarea_padre_id = null;

  public function mount() {
    $this->users  = User::orderBy('name')->get();
    $this->cargarTareas();
  }

  #[On('proyecto-seleccionado')]
  public function proyectoSeleccionado($proyecto_id) {
    $this->proyecto_id = $proyecto_id;
    $this->cargarTareas();
  }

  public function cargarTareas() {
    $this->tareas = Tarea::where('proyecto_id', $this->proyecto_id)
      ->orderBy('id', 'desc')
      ->get();
  }

  public function crearTarea() {
    $this->tarea        = new Tarea();
    $this->asunto       = null;
    $this->descripcion  = null;
    $this->estado_id    = null;
    $this->prioridad_id = null;
    $this->asignado_id  = null;

    $this->isEditing    = true;
  }

  public function guardar() {
    $this->validate([
      'asunto'            => 'required|string|max:255',
      'descripcion'       => 'nullable|string',
      'estado_id'         => 'required|string',
      'prioridad_id'      => 'required|string',
      'asignado_id'       => 'nullable|exists:users,id',
      'fecha_compromiso'  => 'nullable|date',
    ]);

    $this->tarea->asunto            = $this->asunto;
    $this->tarea->descripcion       = $this->descripcion;
    $this->tarea->estado            = $this->estado_id;
    $this->tarea->prioridad         = $this->prioridad_id;
    $this->tarea->proyecto_id       = $this->proyecto_id;
    $this->tarea->user_id           = auth()->id();
    $this->tarea->asignado_id       = $this->asignado_id;
    $this->tarea->fecha_compromiso  = $this->fecha_compromiso;
    $this->tarea->tarea_padre_id    = $this->tarea_padre_id;
    $this->tarea->save();

    $this->isEditing = false;
    $this->reset(['asunto', 'descripcion', 'estado_id', 'prioridad_id', 'asignado_id', 'fecha_compromiso']);
    $this->cargarTareas();
  }

  public function cancelar() {
    $this->isEditing = false;
    $this->reset(['asunto', 'descripcion', 'estado_id', 'prioridad_id', 'asignado_id', 'fecha_compromiso']);
  }
}; ?>

<div>

  <div class="w-full card">
    <div class="card-body">
      <h1 class="mb-6 text-xl font-extrabold tracking-wide">DASHBOARD</h1>

      <livewire:proyectos.proyecto-select />

      <div x-data="{ isEditing: $wire.entangle('isEditing') }">
        <div class="mt-4" x-show="!isEditing">
          {{-- Nueva Tarea --}}
          <x-button
            class="btn btn-primary"
            value="Nueva Tarea"
            icon="icon-[tabler--subtask]"
            wire:click="crearTarea"
            />
        </div>

        {{-- Formulario para tareas --}}
        <div x-show="isEditing" x-cloak>
          <h2 class="mt-4 text-lg font-bold tracking-wide">NUEVA TAREA</h2>
          <form x-show="isEditing"
            class="mt-2"
            wire:submit.prevent="guardar"
            x-cloak
            >
            <x-input
              wire:model="asunto"
              name="asunto"
              label="Asunto"
              placeholder="Tarea de la Filial de ..."
              />
            <div class="mt-4">
              <x-textarea
                wire:model="descripcion"
                label="Descripción"
                placeholder="Detalles de la tarea (opcional)"
                rows="5"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
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

            <div class="mt-4">
              <x-button
                type="submit"
                class="btn btn-primary"
                icon="icon-[tabler--check]"
                value="Guardar Tarea"
                wire:click="guardar"
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
        </div>

        <h2 class="mt-4 text-lg font-bold tracking-wide">TAREAS</h2>
        <ul>
          @foreach ($tareas as $tarea)
            <li>[#{{ $tarea->id }}] {{ $tarea->asunto }} ({{ $tarea->proyecto->nombre ?? null }})</li>
          @endforeach
        </ul>
      </div>

      <div class="h-56 overflow-x-auto">
        <table class="table table-xs table-pin-rows table-pin-cols">
          <thead>
            <tr>
              <th></th>
              <td>Name</td>
              <td>Occupation</td>
              <td>Employer</td>
              <td>Email</td>
              <td>Location</td>
              <td>Last Access</td>
              <td>Favorite Color</td>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>1</th>
              <td>Alice Johnson</td>
              <td>Software Engineer</td>
              <td>Alpha Tech</td>
              <td>alice@example.com</td>
              <td>United States</td>
              <td>12/16/2021</td>
              <td>Blue</td>
              <th>1</th>
            </tr>
            <tr>
              <th>2</th>
              <td>Bob Smith</td>
              <td>Marketing Manager</td>
              <td>Beta Corp</td>
              <td>bob@example.com</td>
              <td>Canada</td>
              <td>11/5/2021</td>
              <td>Green</td>
              <th>2</th>
            </tr>
            <tr>
              <th>3</th>
              <td>Charlie Brown</td>
              <td>Graphic Designer</td>
              <td>Gamma Designs</td>
              <td>charlie@example.com</td>
              <td>United Kingdom</td>
              <td>10/20/2021</td>
              <td>Red</td>
              <th>3</th>
            </tr>
            <tr>
              <th>4</th>
              <td>Dora Johnson</td>
              <td>HR Manager</td>
              <td>Delta Corp</td>
              <td>dora@example.com</td>
              <td>Australia</td>
              <td>9/15/2021</td>
              <td>Purple</td>
              <th>4</th>
            </tr>
            <tr>
              <th>5</th>
              <td>Ethan Hunt</td>
              <td>Secret Agent</td>
              <td>Eagle Eye</td>
              <td>ethan@example.com</td>
              <td>France</td>
              <td>8/10/2021</td>
              <td>Black</td>
              <th>5</th>
            </tr>
            <tr>
              <th>6</th>
              <td>Fiona Brown</td>
              <td>Financial Analyst</td>
              <td>Fox Finance</td>
              <td>fiona@example.com</td>
              <td>Germany</td>
              <td>7/5/2021</td>
              <td>Yellow</td>
              <th>6</th>
            </tr>
            <tr>
              <th>7</th>
              <td>George Wilson</td>
              <td>Project Manager</td>
              <td>Gazelle Projects</td>
              <td>george@example.com</td>
              <td>Brazil</td>
              <td>6/1/2021</td>
              <td>Orange</td>
              <th>7</th>
            </tr>
            <tr>
              <th>8</th>
              <td>Hannah Green</td>
              <td>Environmentalist</td>
              <td>Hunter Foundation</td>
              <td>hannah@example.com</td>
              <td>India</td>
              <td>5/25/2021</td>
              <td>Green</td>
              <th>8</th>
            </tr>
            <tr>
              <th>9</th>
              <td>Ian Black</td>
              <td>Journalist</td>
              <td>Insight News</td>
              <td>ian@example.com</td>
              <td>Japan</td>
              <td>4/20/2021</td>
              <td>Blue</td>
              <th>9</th>
            </tr>
            <tr>
              <th>10</th>
              <td>Jennifer White</td>
              <td>Doctor</td>
              <td>Jupiter Hospital</td>
              <td>jennifer@example.com</td>
              <td>South Africa</td>
              <td>3/15/2021</td>
              <td>White</td>
              <th>10</th>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th></th>
              <td>Name</td>
              <td>Occupation</td>
              <td>Employer</td>
              <td>Email</td>
              <td>Location</td>
              <td>Last Access</td>
              <td>Favorite Color</td>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>



    </div>
  </div>

</div>
