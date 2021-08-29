<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaguDipa extends Model
{
    use \Awobaz\Compoships\Compoships;

    protected $table = 'monira_data_dipa';
    protected $guarded = [];
    // protected $with = ['realdipacovid','kegiatancovid'];

    public function keterangan(){
        return $this->hasOne(RefAkun::class,'KdAkun','Akun');
    }

    public function isCovid(){
        return $this->hasone(RefAkun::class,'KdAkun','Akun')->where('isCovid','1');
    }

    // public function realdipacovid() {
    //     return $this->hasOne(BelanjaDipa::class,['KdSatker','Id'],['KdSatker','Id'])->selectraw('KdSatker,Sum(Amount) as Amount,Akun,SumberDana,Id')->groupby(DB::Raw("KdSatker,Id"));
    // }

    // public function kegiatancovid() {
    //     return $this->hasMany(PaguDipaCovid::class,['KdSatker','Id'],['KdSatker','Id']);
    // }

}
