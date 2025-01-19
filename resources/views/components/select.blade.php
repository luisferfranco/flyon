@props(['options', 'label' => null, 'key' => 'id', 'value' => 'nombre'])

<div class="relative w-full">
  <select
    {{ $attributes->merge(['class' => 'select select-floating peer']) }}
    aria-label="Select floating label"
    >
    <option>Selecciona...</option>
    @foreach ($options as $option)
      <option value="{{ $option[$key] }}">{{ $option[$value] }}</option>
    @endforeach
  </select>

  <label class="select-floating-label">{{ $label }}</label>
</div>
