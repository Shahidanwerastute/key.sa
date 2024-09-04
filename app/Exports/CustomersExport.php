<?php

namespace App\Exports;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Response;

class CustomersExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private $title;
    private $data;

    public function __construct($title, $data)
    {
        $this->title = $title;
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return isset($this->data[0]) ? array_keys($this->data[0]) : [''];
    }
}