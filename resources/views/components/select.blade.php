@props(['name', 'options', 'label' => null, 'key' => 'id', 'value' => 'nombre', 'disabled' => false, 'nullable' => false])

<div class="relative w-full">
  <select
    @if ($disabled) disabled @endif
    {{ $attributes->merge(['class' => 'select select-floating peer' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    aria-label="Select floating label"
    >
    <option @if (!$nullable) disabled @endif>Selecciona...</option>
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
