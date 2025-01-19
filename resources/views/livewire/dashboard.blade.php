<?php

use App\Models\Proyecto;
use Livewire\Volt\Component;

new class extends Component {
  public $proyectos;

  public function mount() {
    $this->proyectos = Proyecto::orderBy('id')->get();
  }
}; ?>

<div>

  <x-button
    class="mb-4 btn btn-primary"
    value="Middle Center"
    aria-haspopup="dialog"
    aria-expanded="false"
    aria-controls="middle-center-modal"
    data-overlay="#middle-center-modal"
    />

  <div
    id="middle-center-modal"
    class="hidden overlay modal overlay-open:opacity-100 modal-middle"
    role="dialog"
    tabindex="-1"
    >
    <div class="modal-dialog overlay-open:opacity-100">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Dialog Title</h3>
          <button type="button" class="absolute btn btn-text btn-circle btn-sm end-3 top-3" aria-label="Close" data-overlay="#middle-center-modal">
            <span class="icon-[tabler--x] size-4"></span>
          </button>
        </div>
        <div class="modal-body">
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Provident, aperiam labore, ex veniam fugiat hic voluptatibus nisi architecto aspernatur, quod cupiditate? Quisquam a quos, excepturi vel ad aliquid pariatur corporis.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-soft btn-secondary" data-overlay="#middle-center-modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>


  <div class="w-full card">
    <div class="card-body">
      <h1 class="mb-6 text-xl font-extrabold tracking-wide">DASHBOARD</h1>

      <div class="flex space-x-2">
        <x-select
          :options="$proyectos"
          label="Selecciona el Proyecto"
          />
        <x-button
          class="btn btn-primary"
          icon="icon-[tabler--circle-plus]"
          />
        <x-button
          class="btn btn-primary"
          icon="icon-[tabler--edit]"
          />
      </div>

    </div>
  </div>

</div>
