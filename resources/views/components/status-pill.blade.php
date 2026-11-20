@props(['status'])
{{-- Coloured availability badge. Labels come from the Equipment model so they stay consistent. --}}
<span class="pill pill-{{ $status }}">{{ \App\Models\Equipment::STATUSES[$status] ?? ucfirst($status) }}</span>
