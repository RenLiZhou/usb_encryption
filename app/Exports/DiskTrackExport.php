<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DiskTrackExport implements FromCollection, WithHeadings, WithStrictNullComparison, WithMapping
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return ['ID', 'u盘ID', '事件名', '事件详情', '	操作IP', '机器码', '时间'];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->disk_id,
            $row->event_name,
            $row->event_desc,
            $row->ip,
            $row->machine_code,
            $row->created_at
        ];
    }
}
