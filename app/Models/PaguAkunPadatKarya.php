<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaguAkunPadatKarya extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'monira_data_pakar_akun';
    protected $guarded = [];
    protected $with    = ['realisasi_akun'];
    public $primaryKey = 'idtable';

    public function Akun(){
        return $this->hasOne(RefAkun::class,'KdAkun','Akun');
    }

    public function realisasi_akun(){
        return $this->belongsTo(BelanjaDipaPadatKarya::class,['idtable','guid', 'Id'],['idtable','guid','Id']);
    }

}
