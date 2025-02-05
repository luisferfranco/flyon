@props(['user', 'size' => 'default'])

@php
  switch ($size) {
    case 'sm':
      $size = 'size-8';
      break;
    case 'md':
      $size = 'size-12';
      break;
    case 'lg':
      $size = 'size-24';
      break;
    default:
      $size = 'size-16';
      break;
  }
@endphp

<div {{ $attributes->merge(['class' => 'avatar']) }}>
  <div class="rounded-full">
    @if ($user->imagen)
      <img src="{{ $user->imagen }}" />
    @else
      <span class="icon-[line-md--person-twotone] {{ $size }}"></span>
    @endif
  </div>
</div>
