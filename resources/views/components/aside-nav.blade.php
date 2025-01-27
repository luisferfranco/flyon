@php
  $opciones = [
    'dashboard' => [
      'link' => route('dashboard'),
      'icon' => 'icon-[fluent-color--home-16]',
      'text' => 'Dashboard',
    ],
    'proyectos' => [
      'link' => '#',
      'icon' => 'icon-[fluent-color--molecule-16]',
      'text' => 'Proyectos',
    ],
    'personal' => [
      'link' => '#',
      'icon' => 'icon-[fluent-color--people-community-16]',
      'text' => 'Personal',
    ],
    'calendario' => [
      'link' => '#',
      'icon' => 'icon-[fluent-color--calendar-16]',
      'text' => 'Calendario',
    ],
  ];
@endphp

<aside class="w-56 h-screen shadow-xl bg-base-100">
  <ul class="mt-4">

    @foreach ($opciones as $opcion)
      <a href="{{ $opcion['link'] }}" wire:navigat]e>
        <li class="flex items-center gap-2 px-6 py-2 transition duration-300 hover:bg-secondary hover:text-secondary-content">
          <span class="{{ $opcion['icon'] }}"></span>
          {{ $opcion['text'] }}
        </li>
      </a>
    @endforeach
  </ul>
</aside>
