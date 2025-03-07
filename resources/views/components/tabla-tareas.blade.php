@props(['tareas'])

<section class="overflow-x-auto">
  <table class="table table-pin-rows">
    <thead>
      <tr>
        <th></th>
        <td>Asunto</td>
        <td>Estado</td>
        <td>Prioridad</td>
        <td>Asignado</td>
        <td>Fecha Compromiso</td>
        <td></td>
      </tr>
    </thead>
    <tbody>
      @foreach ($tareas as $tarea)
        <tr class="hover">
          <th>{{ $tarea->id }}</th>

          {{-- Asunto/Descripción --}}
          <td>
            <div class="max-w-96 text-wrap">
              @for ($i = 0; $i < $tarea->nivel; $i++)
                &nbsp;&nbsp;&nbsp;&nbsp;
              @endfor
              <a href="{{ route('tarea.show', $tarea->id) }}">
                <span class="font-bold text-primary">{{ $tarea->asunto }}</span>
              </a>
            </div>
          </td>

          {{-- Estado --}}
          <td>
            @php
              $estadoColors = [
                'PEND' => 'badge-warning',
                'ENPR' => 'badge-info',
                'COMP' => 'badge-success',
                'RECH' => 'badge-danger',
                'CERR' => 'badge-secondary',
              ];
              $color = $estadoColors[$tarea->estado] ?? 'badge-light';
            @endphp
            <span class="badge {{ $color }}">{{ $tarea->estado }}</span>
          </td>

          {{-- Prioridad --}}
          <td>
            @php
              $prioridadColors = [
                'URGE' => 'badge-error',
                'ALTA' => 'badge-warning',
                'NORM' => 'badge-info',
                'BAJA' => 'badge-secondary',
              ];
              $color = $prioridadColors[$tarea->prioridad] ?? 'badge-light';
            @endphp
            <span class="badge {{ $color }}">{{ $tarea->prioridad }}</span>
          </td>

          {{-- Asignado --}}
          <td>
            @if ($tarea->asignado)
              <a
                href="{{ route('user.show', $tarea->asignado->id) }}"
                class="text-primary"
                wire:navigate
                >
                {{ $tarea->asignado->name ?? null }}
              </a>
            @endif
          </td>

          {{-- Fecha Compromiso --}}
          <td>
            @if ($tarea->fecha_compromiso)
              {{ $tarea->fecha_compromiso->format('Y/m/d') }}
            @endif
          </td>

          {{-- Acciones --}}
          <td class="align-right">
            <div class="flex items-center space-x-1">
              <a
                href="{{ route('tarea.edit', $tarea->id) }}"
                class="font-bold btn btn-primary btn-xs"
                wire:navigate
                >
                <span class="icon-[tabler--pencil]"></span>
              </a>
              <a
                href="{{ route('tarea.create', ['padre' => $tarea]) }}"
                class="font-bold btn btn-primary btn-xs"
                wire:navigate
                >
                <span class="icon-[tabler--subtask]"></span>
              </a>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</section>
