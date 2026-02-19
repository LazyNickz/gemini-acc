<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericExport implements FromCollection, WithHeadings, WithStyles
{
    protected Collection $data;
    protected array $headers;

    public function __construct(Collection $data, array $headers)
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    public function collection(): Collection
    {
        return $this->data->map(function ($item) {
            $row = [];
            foreach ($this->headers as $header) {
                $key = strtolower(str_replace(' ', '_', $header));
                $row[] = $item[$key] ?? ($item->{$key} ?? '');
            }
            return $row;
        });
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
