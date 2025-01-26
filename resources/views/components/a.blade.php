@props(['value' => null, 'icon' => null])

<a
  {{ $attributes->merge(['class' => 'font-bold uppercase tracking-wide']) }}
  >
  @if($icon)
    <span class="{{ $icon }}"></span>
  @endif
  {{ $value ?? $slot }}
</a>
