<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GeneralImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
    }
}
