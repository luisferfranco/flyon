@props(['value', 'icon' => null])

<button
  {{ $attributes->merge(['class' => 'uppercase font-bold tracking-wide shadow-md']) }}
  >
  @if ($icon)
    <span class="{{ $icon }} size-5"></span>
  @endif
  {{ $value ?? $slot}}
</button>
