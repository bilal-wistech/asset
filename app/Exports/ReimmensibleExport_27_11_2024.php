<?php

namespace App\Exports;

use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReimmensibleExport implements FromCollection, WithMapping, WithHeadings
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    public function collection()
    {
        return $this->expenses;
    }
    public function map($expense): array
    {
        return [
            $expense->image ?? '',
            $expense->type != null && $expense->type->title != null ? $expense->type->title : 'Type is not available',
            $expense->userdata != null ? $expense->userdata->username : 'Username is not available',
            $expense->total_milage ?? '',
            $expense->amount ?? '',
            $expense->created_at ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'Image',
            'Type',
            'Username',
            'Total Milage',
            'Amount',
            'Created At'
        ];
    }
}
