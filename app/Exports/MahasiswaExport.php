<?php

namespace App\Exports;

use App\Services\MahasiswaQueryService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MahasiswaExport implements FromCollection, WithHeadings
{
    protected $request;
    protected $tahun;
    protected $service;

    public function __construct($request, $tahun)
    {
        $this->request = $request;
        $this->tahun = $tahun;
        $this->service = app(MahasiswaQueryService::class);
    }

    public function collection()
    {
        return $this->service
            ->baseQuery($this->request, $this->tahun)
            ->selectRaw("
                a.str_nim as NIM,
                a.str_nm_mhs as Nama,
                CASE f.status
                    WHEN 1 THEN 'Aktif'
                    WHEN 2 THEN 'Cuti'
                    WHEN 3 THEN 'Nonaktif'
                    ELSE '-'
                END as Status
            ")
            ->get();
    }

    public function headings(): array
    {
        return ['NIM', 'Nama', 'Status'];
    }
}
