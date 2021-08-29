<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaguDipaCovid extends Model
{
    use \Awobaz\Compoships\Compoships;

    use HasFactory;
    protected $table = 'monira_data_dipa_covid';
    protected $guarded = [];
    // protected $with = ['realkegiatan','sppd'];

    // public function realkegiatan() {
    //     return $this->hasOne(BelanjaDipaCovid::class,'guid','guid')->selectraw('Sum(Volume) as Volume,Sum(Amount) as Amount,guid')->groupby('guid');
    // }
    // public function sppd() {
    //     return $this->hasMany(BelanjaDipaCovid::class,'guid','guid')->selectraw('Nosp2d,Bulan,guid');
    // }

}
