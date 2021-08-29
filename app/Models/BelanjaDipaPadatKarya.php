<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BelanjaDipaPadatKarya extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'monira_data_pakar_belanja';
    protected $guarded = [];
    public $primaryKey = 'idtable';
    protected $with    = ['sppd'];

    public function akun(){
        return $this->belongsTo(PaguAkunPadatKarya::class,['guid', 'Id'],['guid','Id']);
    }
    public function sppd(){
        return $this->hasMany(DataSPDPadatKarya::class,'Id','guid_sppd');
    }



}
