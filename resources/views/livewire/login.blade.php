<?php
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Title('Login')]
class extends Component {

  #[Rule('required|email')]
  public string $email = '';

  #[Rule('required')]
  public string $password = '';

  public $rememberMe = true;

  public function mount()
  {
    // It is logged in
    if (auth()->user()) {
      return $this->redirect('/dashboard');
    }
  }

  public function login() {
    $credentials = $this->validate();

    if (auth()->attempt($credentials, $this->rememberMe)) {
      request()->session()->regenerate();

      return $this->redirectIntended('/dashboard');
    }

    $this->addError('email', 'Las credenciales no existen en nuestros registros');
  }
}
?>

<div class="p-20 mx-auto max-w-7xl">
  <form
    wire:submit.prevent="login"
    class="mx-auto shadow-lg card sm:max-w-sm"
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

      <div class="mt-2">

        <div class="flex gap-2">
          <input
            type="checkbox"
            class="checkbox checkbox-primary"
            wire:model='rememberMe'
            />
          <label class="flex-col items-start pt-0 -mt-1 cursor-pointer label">
            <span class="text-base label-text">Recordarme</span>
            <span class="label-text-alt">Recordar mi usuario y contraseña</span>
          </label>
        </div>

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