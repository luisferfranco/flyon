@props(['name', 'label' => null])

<div class="relative">
  <input
    {{ $attributes->merge(['class' => 'input input-floating peer' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    />
  <label
    class="input-floating-label"
    >
    {{ $label }}
  </label>
  @error($name)
    <span class="label">
      <span class="label-text-alt">{{ $message }}</span>
    </span>
  @enderror
</div>
