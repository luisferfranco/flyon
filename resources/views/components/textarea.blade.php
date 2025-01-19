@props(['label' => ''])

<div class="relative w-full">
  <textarea
    {{ $attributes->merge(['class' => 'textarea textarea-floating peer']) }}
    ></textarea>
  <label class="textarea-floating-label">{{ $label }}</label>
</div>