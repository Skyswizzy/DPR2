@extends('layouts.app')

@section('title', 'Equipment')
@section('heading', 'Equipment')
@section('subheading', $counts['total'].' items · '.$counts['available'].' available · '.$counts['unavailable'].' unavailable')

@section('actions')
    <a href="{{ route('equipment.create') }}" class="btn btn-primary"><x-icon name="plus" /> Add equipment</a>
@endsection

@section('content')
    <div class="toolbar">
        <form method="GET" action="{{ route('equipment.index') }}" class="search" role="search">
            <x-icon name="search" />
            <input type="search" name="search" value="{{ $search }}" placeholder="Search by name" aria-label="Search equipment by name">
        </form>
        @if ($search !== '')
            <a href="{{ route('equipment.index') }}" class="btn btn-quiet"><x-icon name="x" /> Clear search</a>
        @endif
    </div>

    <div class="panel">
        @if ($equipment->isEmpty())
            <div class="empty">
                @if ($search !== '')
                    <h2>No equipment matches “{{ $search }}”</h2>
                    <p>Check the spelling or clear the search.</p>
                    <a href="{{ route('equipment.index') }}" class="btn">Clear search</a>
                @else
                    <h2>No equipment yet</h2>
                    <p>Add your first paddle, ball pack, net or accessory.</p>
                    <a href="{{ route('equipment.create') }}" class="btn btn-primary"><x-icon name="plus" /> Add equipment</a>
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Equipment</th>
                            <th>Category</th>
                            <th class="num">Unit price</th>
                            <th>Availability</th>
                            <th><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equipment as $item)
                            <tr>
                                <td>
                                    <a class="row-link" href="{{ route('equipment.show', $item) }}">{{ $item->equipment_name }}</a>
                                    <div class="sub">{{ \Illuminate\Support\Str::limit($item->description, 72) }}</div>
                                </td>
                                <td>{{ $item->category_label }}</td>
                                <td class="num">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td><x-status-pill :status="$item->availability_status" /></td>
                                <td class="actions">
                                    <a href="{{ route('equipment.show', $item) }}" class="icon-btn" title="View" aria-label="View {{ $item->equipment_name }}"><x-icon name="eye" /></a>
                                    <a href="{{ route('equipment.edit', $item) }}" class="icon-btn" title="Edit" aria-label="Edit {{ $item->equipment_name }}"><x-icon name="pencil" /></a>
                                    <button type="button" class="icon-btn danger" title="Delete" aria-label="Delete {{ $item->equipment_name }}"
                                            data-delete
                                            data-action="{{ route('equipment.destroy', $item) }}"
                                            data-name="{{ $item->equipment_name }}">
                                        <x-icon name="trash" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Simple pager (Laravel's default pager markup needs Tailwind, which this project doesn't use). --}}
            @if ($equipment->hasPages())
                <nav class="pager" aria-label="Pagination">
                    <span>Showing {{ $equipment->firstItem() }}–{{ $equipment->lastItem() }} of {{ $equipment->total() }}</span>
                    <div>
                        @if ($equipment->onFirstPage())
                            <span class="btn is-disabled">Previous</span>
                        @else
                            <a class="btn" href="{{ $equipment->previousPageUrl() }}">Previous</a>
                        @endif

                        @if ($equipment->hasMorePages())
                            <a class="btn" href="{{ $equipment->nextPageUrl() }}">Next</a>
                        @else
                            <span class="btn is-disabled">Next</span>
                        @endif
                    </div>
                </nav>
            @endif
        @endif
    </div>
@endsection
