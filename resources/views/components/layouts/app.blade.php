<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-300">

  {{-- Navbar --}}
  <nav class="gap-4 shadow-lg navbar bg-base-100">

    {{-- Logo --}}
    <div class="items-center navbar-start">
      <a class="px-4 py-2 text-3xl font-bold tracking-wider no-underline rounded-lg link text-neutral link-neutral bg-warning" href="/">
        SEMF
      </a>
    </div>

    {{-- Parte derecha: Busqueda y Avatar --}}
    <div class="flex items-center gap-4 navbar-end">

      {{-- Búsqueda --}}
      <button class="btn btn-sm btn-text btn-circle size-[2.125rem] md:hidden">
        <span class="icon-[tabler--search] size-[1.375rem]"></span>
      </button>
      <div class="hidden rounded-full input-group max-w-56 md:flex">
        <span class="input-group-text">
          <span class="icon-[tabler--search] text-base-content/80 size-5"></span>
        </span>
        <label class="sr-only" for="searchInput">Full Name</label>
        <input type="search" id="searchInput" class="input grow rounded-e-full" placeholder="Search" />
      </div>

      @auth
        {{-- Dropdown (Avatar del usuario) --}}
        <div class="dropdown relative inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom-end]">
          <button id="dropdown-scrollable" type="button" class="flex items-center dropdown-toggle" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
            <div class="avatar">
              <div class="size-9.5 rounded-full">
                <img src="https://cdn.flyonui.com/fy-assets/avatar/avatar-1.png" alt="avatar 1" />
              </div>
            </div>
          </button>
          {{-- Opciones para el usuario --}}
          <ul class="hidden dropdown-menu dropdown-open:opacity-100 min-w-60" role="menu" aria-orientation="vertical" aria-labelledby="dropdown-avatar">

            {{-- ID del Usuario --}}
            <li class="gap-2 dropdown-header">
              <div class="avatar">
                <div class="w-10 rounded-full">
                  <img src="https://cdn.flyonui.com/fy-assets/avatar/avatar-1.png" alt="avatar" />
                </div>
              </div>
              <div>
                <h6 class="text-base font-semibold text-base-content">{{ auth()->user()->name }}</h6>
                <small class="text-base-content/50">{{ auth()->user()->email }}</small>
              </div>
              <div>
                [[{{ auth()->user()->avatar }}]]
              </div>
            </li>

            {{-- Perfil --}}
            <li>
              <a class="dropdown-item" href="#">
                <span class="icon-[tabler--user]"></span>
                Mi Perfil
              </a>
            </li>

            {{-- Claro/Oscuro --}}
            <li>
              <label class="relative inline-block ml-4">
                <input type="checkbox" value="dark" class="switch switch-primary theme-controller peer" />
                <span class="icon-[tabler--sun] peer-checked:text-primary-content absolute start-1 top-1 block size-4"></span>
                <span class="icon-[tabler--moon] text-base-content peer-checked:text-base-content absolute end-1 top-1 block size-4" ></span>
              </label>
            </li>

            {{-- Logout --}}
            <li class="gap-2 dropdown-footer">
              <a class="btn btn-error btn-soft btn-block" href="{{ route('logout') }}" >
                <span class="icon-[tabler--logout]"></span>
                Salir
              </a>
            </li>
          </ul>
        </div>
      @else
        {{-- Login y Register --}}
        <div class="flex gap-4">
          <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login</a>
          <a href="{{ route('register') }}" class="btn btn-sm btn-secondary">Register</a>
        </div>
      @endauth
    </div>
  </nav>


  <div class="flex">
    <aside class="w-56 h-screen shadow-xl bg-base-100">
      <ul class="mt-4">
        <a
          href="{{ route('dashboard') }}"
          wire:navigate
          >
          <li class="flex items-center gap-2 px-6 py-2 transition duration-300 hover:bg-primary hover:text-primary-content">
            <span class="icon-[fluent-color--home-16]"></span>
            Dashboard
          </li>
        </a>
        <a href="#" wire:navigate>
          <li class="flex items-center gap-2 px-6 py-2 transition duration-300 hover:bg-primary hover:text-primary-content">
            <span class="icon-[fluent-color--molecule-16]"></span>
            Proyectos
          </li>
        </a>
        <a href="#" wire:navigate>
          <li class="flex items-center gap-2 px-6 py-2 transition duration-300 hover:bg-primary hover:text-primary-content">
            <span class="icon-[fluent-color--people-community-16]"></span>
            Personal
          </li>
        </a>
      </ul>
    </aside>

    <div class="w-full p-6 mx-auto max-w-7xl">
      {{ $slot }}
    </div>
  </div>
</body>
</html>