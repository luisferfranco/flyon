@props(['name', 'options', 'label' => null, 'key' => 'id', 'value' => 'nombre'])

<div class="relative w-full">
  <select
    {{ $attributes->merge(['class' => 'select select-floating peer' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    aria-label="Select floating label"
    >
    <option value="0">Selecciona...</option>
    @foreach ($options as $option)
      <option value="{{ $option[$key] }}">{{ $option[$value] }}</option>
    @endforeach
  </select>

  <label class="select-floating-label">{{ $label }}</label>
  @error($name)
    <span class="label">
      <span class="label-text-alt">{{ $message }}</span>
    </span>
  @enderror
</div>
