<?php
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Hash;

new
#[Title('Login')]
class extends Component {
  #[Rule('required')]
  public string $name = '';

  #[Rule('required|email|unique:users')]
  public string $email = '';

  #[Rule('required|confirmed')]
  public string $password = '';

  #[Rule('required')]
  public string $password_confirmation = '';

  public function mount()
  {
    // It is logged in
    if (auth()->user()) {
      return $this->redirect('/dashboard');
    }
  }

  public function register()
  {
    $data = $this->validate();

    $data['avatar'] = '/empty-user.jpg';
    $data['password'] = Hash::make($data['password']);

    $user = User::create($data);

    auth()->login($user);

    request()->session()->regenerate();

    return redirect('/');
  }
} ?>

<div class="mx-auto mt-20 md:w-96">
  <form
    wire:submit='register'
    class="mx-auto card sm:max-w-sm"
    >

    <div class="card-body">
      <x-input
        type="text"
        wire:model='name'
        name="name"
        label="Nombre"
        placeholder="Juan Camaney"
        />

      <div class="mt-2">
        <x-input
          type="text"
          wire:model='email'
          name="email"
          label="Correo Electrónico"
          placeholder="juan.camaney@tango.com"
          />
      </div>

      <div class="mt-2">
        <x-input
          type="password"
          wire:model='password'
          name="password"
          label="Contraseña"
          placeholder="**********"
          />
      </div>

      <div class="mt-2">
        <x-input
          type="password"
          wire:model='password_confirmation'
          name="password_confirmation"
          label="Confirmar Contraseña"
          placeholder="**********"
          />
      </div>

      <div class="flex justify-end mt-4 card-actions">
        <button type="submit" class="btn btn-primary btn-gradient">Registrarme</button>
        <a href="/login"
          class="btn btn-secondary btn-text"
          wire:navigate
          >
          Ya tengo cuenta
        </a>
      </div>
    </div>

  </form>

</div>
