@props(['tareas', 'title' => null])

@php
  $color = [
    'pendiente' => 'bg-yellow-500',
    'en_proceso' => 'bg-blue-500',
    'completado' => 'bg-green-500',
    'cancelado' => 'bg-red-500',
  ];
@endphp

<div class="card">
  <div class="card-body">
    @if ($title)
      <div class="mb-2 card-title">{{ $title }}</div>
    @endif

    <div class="overflow-x-auto h-96">
      <table class="table table-pin-rows table-pin-cols">
        <thead>
          <tr>
            <th></th>
            <td>Asunto</td>
            <td>Estado</td>
            <td>Fecha Compromiso</td>
          </tr>
        </thead>

        <tbody>
          @foreach ($tareas as $tarea)
            <tr class="hover">
              <th>{{ $tarea->id }}</th>
              <td>
                <a
                  href="{{ route('tarea.show', $tarea->id) }}"
                  class="link link-accent"
                  >
                  {{ $tarea->asunto }}
                </a>
              </td>
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
              <td>
                {{ \Carbon\Carbon::parse($tarea->fecha_compromiso)->format('Y/m/d') }}
              </td>
            </tr>
          @endforeach
        </tbody>

      </table>
    </div>
  </div>
</div>
