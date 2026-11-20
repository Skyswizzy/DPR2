<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        /* DejaVu Sans ships with DomPDF and includes the peso sign. No flexbox/grid: DomPDF doesn't support them. */
        @page { margin: 36px 40px; }
        body { font-family: "DejaVu Sans", sans-serif; font-size: 10.5px; color: #16181a; }
        .brand { font-size: 10px; color: #1f6f4a; font-weight: bold; letter-spacing: .5px; text-transform: uppercase; }
        h1 { font-size: 20px; margin: 4px 0 6px; }
        .meta { color: #62666b; margin-bottom: 18px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #f1f1ec; border-bottom: 1.5px solid #16181a; padding: 7px 8px; font-size: 10px; }
        td { padding: 7px 8px; border-bottom: 1px solid #e3e3dc; }
        .num { text-align: right; }
        .status-available { color: #1f6f4a; font-weight: bold; }
        .status-unavailable { color: #62666b; }
        .empty { padding: 30px 0; text-align: center; color: #62666b; }
    </style>
</head>
<body>
    <div class="brand">Pickleball Equipment Inventory</div>
    <h1>{{ $title }}</h1>
    <div class="meta">
        {{ $summary }}<br>
        {{ $items->count() }} {{ $items->count() === 1 ? 'record' : 'records' }} · Generated {{ now()->format('M j, Y g:i A') }}
    </div>

    @if ($items->isEmpty())
        <div class="empty">No equipment matches these filters.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:6%">No.</th>
                    <th>Equipment</th>
                    <th>Category</th>
                    <th class="num">Unit price</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->equipment_name }}</td>
                        <td>{{ $item->category_label }}</td>
                        <td class="num">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td class="status-{{ $item->availability_status }}">{{ $item->status_label }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
