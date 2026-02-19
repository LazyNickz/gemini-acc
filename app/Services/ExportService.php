<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    public function exportCSV(Collection $data, string $filename, array $headers): BinaryFileResponse|\Illuminate\Http\Response
    {
        $csv = implode(',', $headers) . "\n";

        foreach ($data as $row) {
            $values = [];
            foreach (array_values($row) as $cell) {
                $values[] = '"' . str_replace('"', '""', (string) $cell) . '"';
            }
            $csv .= implode(',', $values) . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ]);
    }

    public function exportExcel(Collection $data, string $filename, array $headers)
    {
        return Excel::download(new GenericExport($data, $headers), "{$filename}.xlsx");
    }

    public function exportPDF(Collection $data, string $filename, string $view, array $extra = [])
    {
        $pdf = Pdf::loadView($view, [
            'data' => $data,
            'title' => $extra['title'] ?? 'Export',
            'headings' => $extra['headers'] ?? [],
        ]);
        return $pdf->download("{$filename}.pdf");
    }
}

