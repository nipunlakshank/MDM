<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function map($item): array
    {
        return [
            $item->code,
            $item->category ? $item->category->name : '',
            $item->brand ? $item->brand->name : '',
            $item->name,
            $item->attachment,
            $item->status,
        ];
    }

    public function headings(): array
    {
        return ['Code', 'Category', 'Brand', 'Name', 'Attachment', 'Status'];
    }
}
