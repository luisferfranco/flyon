<?php
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.empty')]       // <-- Here is the `empty` layout
#[Title('Login')]
class extends Component {

  #[Rule('required|email')]
  public string $email = '';

  #[Rule('required')]
  public string $password = '';

  public function mount()
  {
    // It is logged in
    if (auth()->user()) {
      return redirect('/');
    }
  }

  public function login() {
    $credentials = $this->validate();
    info($credentials);

    if (auth()->attempt($credentials)) {
      request()->session()->regenerate();

      return redirect()->intended('/');
    }

    $this->addError('email', 'Las credenciales no existen en nuestros registros');
  }
}
?>


<div class="p-20 mx-auto max-w-7xl">
  <form
    wire:submit.prevent="login"
    class="card sm:max-w-sm"
    >

    <div class="card-body">
      <div class="mb-2 card-title">Ingresar</div>

      <div class="relative">
        <input
          type="text"
          placeholder="juan.camaney@tango.com"
          class="input input-floating peer @error('email') is-invalid @enderror"
          wire:model="email"
          />
        <label
          class="input-floating-label"
          for="email"
          >
          Correo Electrónico
        </label>
        @error('email')
          <span class="label">
            <span class="label-text-alt">{{ $message }}</span>
          </span>
        @enderror
      </div>

      <div class="relative mt-4">
        <input
          type="password"
          placeholder="**********"
          class="input input-floating peer @error('password') is-invalid @enderror"
          wire:model="password"
          />
        <label
          class="input-floating-label"
          for="password"
          >
          Password
        </label>
        @error('password')
          <span class="label">
            <span class="label-text-alt">{{ $message }}</span>
          </span>
        @enderror
      </div>

      <div class="flex justify-end mt-4 card-actions">
        <button type="submit" class="btn btn-primary btn-gradient">Ingresar</button>
        <a href="/register"
          class="btn btn-secondary btn-text"
          wire:navigate
          >
          Crear una cuenta
        </a>
      </div>
    </div>

  </form>
</div>