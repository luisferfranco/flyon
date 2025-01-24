@props(['label' => '', 'name' => ''])

<div class="relative w-full">
  <textarea
    {{ $attributes->merge(['class' => 'textarea textarea-floating peer' .
    ($errors->has($name) ? ' is-invalid' : '')]) }}
    ></textarea>
  <label class="textarea-floating-label">{{ $label }}</label>
  @if($name)
    @error($name)
      <span class="label">
        <span class="label-text-alt">{{ $message }}</span>
      </span>
    @enderror
  @endif
</div>