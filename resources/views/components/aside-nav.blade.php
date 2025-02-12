@php
  $opciones = [
    'dashboard' => [
      'link' => route('dashboard'),
      'icon' => 'icon-[line-md--gauge-loop]',
      'text' => 'Dashboard',
    ],
    'proyectos' => [
      'link' => route('proyectos.index'),
      'icon' => 'icon-[fluent-color--molecule-16]',
      'text' => 'Proyectos',
    ],
    'Tareas' => [
      'link' => route('proyecto.show'),
      'icon' => 'icon-[line-md--check-list-3-filled]',
      'text' => 'Tareas',
    ],
    'calendario' => [
      'link' => '#',
      'icon' => 'icon-[line-md--text-box-twotone-to-text-box-multiple-twotone-transition]',
      'text' => 'Calendario',
    ],
  ];
@endphp

<aside class="w-56 h-full min-h-screen shadow-xl bg-base-100">
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
