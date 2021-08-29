<?php

namespace App;

use App\Models\PaguDipa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class Import implements ToModel,WithStartRow,WithChunkReading
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    use RemembersRowNumber;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        set_time_limit(300);

        $currentRowNumber = $this->getRowNumber();

        return new PaguDipa([
           'TA'         => $this->year,
           'Belanja'    => substr($row[3],0,2) ?? '',
           'Ba'         => $row[1] ?? '',
           'BaEs1'      => $row[2] ?? '',
           'KdSatker'   => $row[0] ?? '',
           'Program'    => $row[4] ?? '',
           'Kegiatan'   => $row[5] ?? '',
           'Output'     => $row[6] ?? '',
           'Akun'       => $row[3] ?? '',
           'Kewenangan' => $row[7] ?? '',
           'SumberDana' => $row[8] ?? '',
           'CaraTarik'  => $row[9] ?? '',
           'KdRegister' => $row[10] ?? '',
           'Lokasi'     => $row[11] ?? '',
           'BudgetType' => $row[12] ?? '',
           'Amount'     => $row[13] ?? '',
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

}
