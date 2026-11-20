@props(['name'])
{{-- Small inline icon set (outline style, drawn on a 24px grid). Usage: <x-icon name="plus" /> --}}
<svg {{ $attributes->merge(['class' => 'icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('plus') <path d="M12 5v14M5 12h14"/> @break
        @case('pencil') <path d="M16.9 4.6a2 2 0 0 1 2.8 2.8L8.5 18.6 4 20l1.4-4.5L16.9 4.6z"/> @break
        @case('eye') <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/> @break
        @case('trash') <path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12M9 7V4h6v3"/> @break
        @case('arrow-left') <path d="M19 12H5M11 6l-6 6 6 6"/> @break
        @case('box') <path d="M3 7.5 12 3l9 4.5v9L12 21l-9-4.5v-9zM3 7.5l9 4.5 9-4.5M12 12v9"/> @break
        @case('report') <path d="M5 20V10M12 20V4M19 20v-7"/> @break
        @case('logout') <path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3M10 17l-5-5 5-5M5 12h11"/> @break
        @case('search') <circle cx="11" cy="11" r="6"/><path d="M20 20l-4-4"/> @break
        @case('file') <path d="M7 3h7l5 5v13H7zM14 3v5h5"/> @break
        @case('sheet') <path d="M4 5h16v14H4zM4 10h16M10 5v14"/> @break
        @case('x') <path d="M6 6l12 12M18 6L6 18"/> @break
        @case('check') <path d="M5 12.5l4.5 4.5L19 7.5"/> @break
    @endswitch
</svg>
