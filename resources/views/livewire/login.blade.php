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
    class="mx-auto card sm:max-w-sm"
    >

    <div class="card-body">
      <div class="mb-2 card-title">Ingresar</div>

      <x-input
        type="text"
        wire:model="email"
        name="email"
        placeholder="juan.camaney@tango.com"
        label="Correo Electrónico"
        />

      <div class="mt-2">
        <x-input
          type="password"
          wire:model="password"
          name="password"
          placeholder="**********"
          label="Contraseña"
          />
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