<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AssetMaintenancesExport implements FromCollection, WithMapping, WithHeadings
{
    protected $maintenances;

    public function __construct($maintenances)
    {
        $this->maintenances = $maintenances;
    }

    public function collection()
    {
        return $this->maintenances;
    }
    public function map($maintenance): array
    {
        return [
            $maintenance->asset->company->name ?? '',
            $maintenance->asset->asset_tag ?? '',
            $maintenance->asset->name ?? '',
            $maintenance->supplier->name ?? '',
            $maintenance->title ?? '',
            $maintenance->asset_maintenance_type ?? '',
            $maintenance->start_date ?? '',
            $maintenance->completion_date ?? '',
            $maintenance->asset_maintenance_time ?? '',
            $maintenance->cost ?? '',
            $maintenance->asset->location->name ?? '',
            $maintenance->asset->defaultLoc->name ?? '',
            $maintenance->asset->admin->name ?? '',
            $maintenance->notes ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'Company',
            'Asset Tag',
            'Asset Name',
            'Supplier',
            'Asset Maintenance Type',
            'Title',
            'Start Date',
            'Completion Date',
            'Asset Maintenance Time',
            'Cost',
            'Location',
            'Default Location',
            'Admin',
            'Notes'
        ];
    }
}
