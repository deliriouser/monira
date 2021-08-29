<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefBelanja extends Model
{
    use HasFactory;

    protected $table = 'monira_ref_belanja';

    public function pagu(){
        return $this->hasOne(PaguDipa::class,'Belanja','id')->selectraw('Belanja,sum(Amount) as jumlah')->where('IsActive','1')->groupby('Belanja');
    }

    public function realisasi(){
        return $this->hasOne(BelanjaDipa::class,'Belanja','id')->selectraw('Belanja,sum(Amount) as jumlah')->groupby('Belanja');
    }

}
