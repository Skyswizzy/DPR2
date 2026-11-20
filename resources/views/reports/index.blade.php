@extends('layouts.app')

@section('title', 'Reports')
@section('heading', 'Reports')
@section('subheading', 'Built from saved equipment records. Exports follow the filters below.')

@section('content')
    {{-- Report type. Switching tabs keeps the category; status is kept only if the new tab doesn't fix it. --}}
    <nav class="tabs" aria-label="Report type">
        @foreach ($reports as $key => $label)
            <a href="{{ route('reports.index', array_filter([
                    'report' => $key,
                    'category' => $filters['category'],
                    'status' => $filters['pinned'] ? null : $filters['status'],
                ])) }}"
               class="tab {{ $filters['report'] === $key ? 'is-active' : '' }}"
               @if ($filters['report'] === $key) aria-current="page" @endif>
                {{ $label }}
            </a>
        @endforeach
    </nav>

    <div class="report-bar">
        <form method="GET" action="{{ route('reports.index') }}" class="filters">
            <input type="hidden" name="report" value="{{ $filters['report'] }}">

            <div class="field">
                <label for="category">Category</label>
                <select id="category" name="category" onchange="this.form.submit()">
                    <option value="">All categories</option>
                    @foreach (\App\Models\Equipment::CATEGORIES as $value => $label)
                        <option value="{{ $value }}" @selected($filters['category'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="status">Availability</label>
                {{-- Disabled on the Available / Unavailable reports, where the report itself sets the status. --}}
                <select id="status" name="status" onchange="this.form.submit()" @disabled($filters['pinned'])>
                    <option value="">All</option>
                    @foreach (\App\Models\Equipment::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @if ($filters['pinned'])
                    <span class="hint">Set by the report type</span>
                @endif
            </div>

            <a href="{{ route('reports.index', ['report' => $filters['report']]) }}" class="btn"><x-icon name="x" /> Reset filters</a>
            <noscript><button type="submit" class="btn btn-primary">Apply</button></noscript>
        </form>

        {{-- Export links reuse the exact same query string, so the files match this screen. --}}
        <div class="exports">
            @if ($items->isEmpty())
                <span class="btn is-disabled" aria-disabled="true"><x-icon name="file" /> PDF</span>
                <span class="btn is-disabled" aria-disabled="true"><x-icon name="sheet" /> Excel</span>
            @else
                <a href="{{ route('reports.pdf', $exportQuery) }}" class="btn"><x-icon name="file" /> PDF</a>
                <a href="{{ route('reports.excel', $exportQuery) }}" class="btn"><x-icon name="sheet" /> Excel</a>
            @endif
        </div>
    </div>

    <div class="panel">
        <div class="report-meta">
            <div>
                <h2>{{ $title }}</h2>
                <p>{{ $summary }}</p>
            </div>
            <span class="count-chip">{{ $items->count() }} {{ $items->count() === 1 ? 'record' : 'records' }}</span>
        </div>

        @if ($items->isEmpty())
            <div class="empty">
                <h2>No equipment matches these filters</h2>
                <p>Try a different category or availability, or reset the filters.</p>
                <a href="{{ route('reports.index', ['report' => $filters['report']]) }}" class="btn">Reset filters</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Equipment</th>
                            <th>Category</th>
                            <th class="num">Unit price</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="muted">{{ $loop->iteration }}</td>
                                <td>{{ $item->equipment_name }}</td>
                                <td>{{ $item->category_label }}</td>
                                <td class="num">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td><x-status-pill :status="$item->availability_status" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
