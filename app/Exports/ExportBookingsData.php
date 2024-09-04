<?php

namespace App\Exports;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Response;

class ExportBookingsData implements ShouldAutoSize, WithMultipleSheets
{
    private $sheets;

    public function __construct($sheets)
    {
        $this->sheets = $sheets;
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}