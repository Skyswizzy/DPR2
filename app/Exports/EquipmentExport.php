<?php

namespace App\Exports;

use App\Models\Equipment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Excel export. It receives the already-filtered collection from ReportController,
 * so the spreadsheet always matches the report on screen.
 */
class EquipmentExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    private int $rowNumber = 0;

    public function __construct(private Collection $items)
    {
    }

    public function collection(): Collection
    {
        return $this->items;
    }

    public function headings(): array
    {
        return ['No.', 'Equipment name', 'Category', 'Unit price', 'Availability'];
    }

    /** @param Equipment $item */
    public function map($item): array
    {
        return [
            ++$this->rowNumber,
            $item->equipment_name,
            $item->category_label,
            (float) $item->unit_price, // a real number, so Excel can sort and sum it
            $item->status_label,
        ];
    }

    public function columnFormats(): array
    {
        return ['D' => '#,##0.00'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }

    public function title(): string
    {
        return 'Equipment';
    }
}
