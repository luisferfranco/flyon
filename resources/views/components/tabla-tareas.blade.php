@props(['tareas'])

<section class="overflow-x-auto h-3/5">
  <table class="table table-pin-rows table-pin-cols">
    <thead>
      <tr>
        <th></th>
        <td>Asunto</td>
        <td>Estado</td>
        <td>Prioridad</td>
        <td>Asignado</td>
        <td>Fecha Compromiso</td>
        <td></td>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($tareas as $tarea)
        <tr class="hover">
          <th>{{ $tarea->id }}</th>

          {{-- Asunto/Descripción --}}
          <td class="max-w-96">
            <div>
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
          <td>{{ $tarea->asignado->name ?? null }}</td>

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
                href="{{ route('tarea.create') }}"
                class="font-bold btn btn-primary btn-xs"
                wire:navigate
                >
                <span class="icon-[tabler--subtask]"></span>
              </a>
            </div>
          </td>
          <th>{{ $tarea->id }}</th>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th></th>
        <td>Asunto</td>
        <td>Estado</td>
        <td>Prioridad</td>
        <td>Asignado</td>
        <td>Fecha Compromiso</td>
        <td></td>
        <th></th>
      </tr>
    </tfoot>
  </table>
</section>
