<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefNamaBulan extends Model
{
    use HasFactory;
    protected $table = 'monira_ref_bulan';
    protected $with  = ['rpd','mp','dsa'];

    public function rpd(){
        return $this->hasOne(DataRpdmp::class,'bulan','id')->selectraw('bulan,sum(jumlah) as jumlah')->groupby('bulan')->groupby('tahun');
    }

    public function mp(){
        return $this->hasOne(DataMP::class,'bulan','id')->selectraw('bulan,sum(Amount) as Amount')->groupby('bulan')->groupby('TA');
    }

    public function dsa(){
        return $this->hasOne(BelanjaDipa::class,'Bulan','id')->selectraw('Bulan,sum(Amount) as Amount')->groupby('Bulan')->groupby('TA');
    }

    public function semp(){
        return $this->hasMany(DataSEMP::class,'Bulan','id');
    }


}
