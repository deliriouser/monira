<?php

namespace App;

use App\Models\DataKomitmen;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportPrognosa implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function __construct($year)
    {
        $this->year = $year;
    }

    public function model(array $row)
    {

        return new DataKomitmen([
           'TA'          => $this->year,
           'KdSatker'    => $row[0] ?? '',
           'Amount'      => $row[1] ?? '',
           'Persen'      => ($row[2] ?? '0'),
        ]);

    }

}
