<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaguDipaPadatKarya extends Model
{
    use HasFactory;
    protected $table   = 'monira_data_pakar_dipa';
    protected $guarded = [];
    protected $with    = ['lokasi','realisasi'];

    public function akun(){
        return $this->hasMany(PaguAkunPadatKarya::class,'guid','guid');
    }

    public function lokasi(){
        return $this->hasOne(RefDesa::class,'kode','KdLokasi');
    }

    public function realisasi(){
        return $this->hasMany(BelanjaDipaPadatKarya::class,'guid','guid');
    }

    public function sumrealisasi(){
        return $this->hasOne(BelanjaDipaPadatKarya::class,'guid','guid')
        ->selectraw('
            Jadwal,
            Mekanisme,
            sum(JumlahOrang) as JumlahOrang,
            sum(JumlahHari) as JumlahHari,
            sum(JumlahOrangHari) as JumlahOrangHari,
            avg(UpahHarian) as UpahHarian,
            (sum(TotalBiayaUpah)/sum(TotalPagu))*100 as PersenBiayaUpah,
            sum(TotalBiayaUpah) as TotalBiayaUpah,
            sum(TotalBiayaLain) as TotalBiayaLain,
            sum(TotalPagu) as TotalPagu,
            sum(TotalPaguDipa) as TotalPaguDipa,
            Keterangan,
            guid
        ')->groupby('guid');
    }



}
