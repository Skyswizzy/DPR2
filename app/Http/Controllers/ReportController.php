<?php

namespace App\Http\Controllers;

use App\Exports\EquipmentExport;
use App\Models\Equipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reports are built only from saved equipment rows, so every add, edit and delete
 * shows up the next time a report (or an export) is generated.
 *
 * The page, the PDF and the Excel file all call the same two private methods
 * (resolveFilters + records). That is what guarantees an export always matches
 * what is on screen.
 */
class ReportController extends Controller
{
    /** The four required reports. */
    private const REPORTS = [
        'price' => 'Equipment Price List',
        'available' => 'Available Equipment',
        'unavailable' => 'Unavailable Equipment',
        'category' => 'Equipment by Category',
    ];

    public function index(Request $request): View
    {
        $filters = $this->resolveFilters($request);
        $items = $this->records($filters);

        return view('reports.index', [
            'reports' => self::REPORTS,
            'filters' => $filters,
            'items' => $items,
            'title' => $this->title($filters),
            'summary' => $this->summary($filters),
            // Query string for the export buttons: same report, same filters.
            'exportQuery' => $this->exportQuery($filters),
        ]);
    }

    public function pdf(Request $request): Response
    {
        $filters = $this->resolveFilters($request);

        return Pdf::loadView('reports.pdf', [
            'items' => $this->records($filters),
            'title' => $this->title($filters),
            'summary' => $this->summary($filters),
        ])
            ->setPaper('a4', 'portrait')
            ->download($this->filename($filters, 'pdf'));
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $filters = $this->resolveFilters($request);

        return Excel::download(
            new EquipmentExport($this->records($filters)),
            $this->filename($filters, 'xlsx')
        );
    }

    /**
     * Reads the report type and filters from the query string.
     * Unknown values are ignored instead of causing errors.
     */
    private function resolveFilters(Request $request): array
    {
        $report = $this->allowed($request->query('report'), self::REPORTS) ?? 'price';
        $category = $this->allowed($request->query('category'), Equipment::CATEGORIES);
        $status = $this->allowed($request->query('status'), Equipment::STATUSES);

        // Business logic: the "Available" and "Unavailable" reports fix the status,
        // so any status chosen in the URL is overridden. Category still applies.
        $pinned = in_array($report, ['available', 'unavailable'], true);
        if ($pinned) {
            $status = $report;
        }

        return compact('report', 'category', 'status', 'pinned');
    }

    /** Returns $value only if it is a string that is a key of $options; otherwise null. */
    private function allowed(mixed $value, array $options): ?string
    {
        return is_string($value) && array_key_exists($value, $options) ? $value : null;
    }

    /** Runs the query for the resolved filters. Category and status combine with AND. */
    private function records(array $filters): Collection
    {
        return Equipment::query()
            ->filter($filters['category'], $filters['status'])
            ->orderBy('category')
            ->orderBy('equipment_name')
            ->get();
    }

    private function title(array $filters): string
    {
        $title = self::REPORTS[$filters['report']];

        if ($filters['report'] === 'category' && $filters['category']) {
            $title .= ' — '.Equipment::CATEGORIES[$filters['category']];
        }

        return $title;
    }

    /** One-line description of the active filters, shown on screen and printed in the PDF. */
    private function summary(array $filters): string
    {
        $category = $filters['category'] ? Equipment::CATEGORIES[$filters['category']] : 'All categories';
        $status = $filters['status'] ? Equipment::STATUSES[$filters['status']] : 'All availability';

        return "Category: {$category}  ·  Availability: {$status}";
    }

    private function exportQuery(array $filters): array
    {
        return array_filter([
            'report' => $filters['report'],
            'category' => $filters['category'],
            'status' => $filters['status'],
        ]);
    }

    /** e.g. pickleball-price-paddles-available-20261120-103000.pdf */
    private function filename(array $filters, string $extension): string
    {
        $parts = array_filter([
            'pickleball',
            $filters['report'],
            $filters['category'],
            $filters['pinned'] ? null : $filters['status'],
            now()->format('Ymd-His'),
        ]);

        return Str::slug(implode('-', $parts)).'.'.$extension;
    }
}
