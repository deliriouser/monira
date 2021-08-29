<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataProfileSatker extends Model
{
    use HasFactory;
    protected $table = 'monira_ref_satker';
    protected $with = ['wilayah'];

    public function user(){
        return $this->hasMany(User::class,'kdsatker','KodeSatker');
    }

    public function wilayah() {
        return $this->hasOne(RefWilayah::class,'KodeWilayah','KodeWilayah');
    }

    public function datapadatkarya() {
        return $this->hasMany(PaguDipaPadatKarya::class,'KdSatker','KodeSatker');
    }

    public function pagupadatkarya() {
        return $this->hasone(PaguDipaPadatKarya::class,'KdSatker','KodeSatker')->selectraw('KdSatker,Jadwal,Mekanisme,sum(JumlahOrang) as JumlahOrang,sum(JumlahHari) as JumlahHari,sum(JumlahOrangHari) as JumlahOrangHari,AVG(UpahHarian) as UpahHarian,SUM(TotalBiayaUpah) as TotalBiayaUpah,sum(TotalBiayaLain) as TotalBiayaLain,PersenBiayaUpah,sum(TotalPagu) as TotalPagu')->groupby('KdSatker');
    }
    public function realisasipadatkarya() {
        return $this->hasone(BelanjaDipaPadatKarya::class,'KdSatker','KodeSatker')->selectraw('KdSatker,Jadwal,Mekanisme,sum(JumlahOrang) as JumlahOrang,sum(JumlahHari) as JumlahHari,sum(JumlahOrangHari) as JumlahOrangHari,AVG(UpahHarian) as UpahHarian,SUM(TotalBiayaUpah) as TotalBiayaUpah,sum(TotalBiayaLain) as TotalBiayaLain,PersenBiayaUpah,sum(TotalPagu) as TotalPagu')->groupby('KdSatker');
    }

    public function pagupnbp() {
        return $this->hasone(PaguDipa::class,'KdSatker','KodeSatker')->selectraw('KdSatker,sum(Amount) as Jumlah')->where('SumberDana','D')->where('isActive','1')->groupby('KdSatker');
    }
    public function belanjapnbp() {
        return $this->hasone(BelanjaDipa::class,'KdSatker','KodeSatker')->selectraw('KdSatker,sum(Amount) as Jumlah')->where('SumberDana','D')->groupby('KdSatker');
    }
    public function rpdpnbp() {
        return $this->hasOne(DataRpdmp::class,'kode_satker','KodeSatker')->selectraw('kode_satker,
        sum(case when bulan=1 then jumlah else 0 end) as JAN,
        sum(case when bulan=2 then jumlah else 0 end) as FEB,
        sum(case when bulan=3 then jumlah else 0 end) as MAR,
        sum(case when bulan=4 then jumlah else 0 end) as APR,
        sum(case when bulan=5 then jumlah else 0 end) as MEI,
        sum(case when bulan=6 then jumlah else 0 end) as JUN,
        sum(case when bulan=7 then jumlah else 0 end) as JUL,
        sum(case when bulan=8 then jumlah else 0 end) as AGS,
        sum(case when bulan=9 then jumlah else 0 end) as SEP,
        sum(case when bulan=10 then jumlah else 0 end) as OKT,
        sum(case when bulan=11 then jumlah else 0 end) as NOV,
        sum(case when bulan=12 then jumlah else 0 end) as DES,
        sum(jumlah) as TOTAL
        ')->groupby('kode_satker');
    }
    public function mp() {
        return $this->hasOne(DataMP::class,'KdSatker','KodeSatker')->selectraw('KdSatker,sum(Amount) as Jumlah')->groupby('KdSatker');
    }

    // public function pagucovid() {
    //     return $this->hasMany(PaguDipa::class,'KdSatker','KodeSatker')->selectraw('KdSatker,Sum(Amount) as Amount,Akun,SumberDana,Id')->where('isActive','1')->groupby(DB::Raw("KdSatker,Id"));
    // }

    public function files() {
        return $this->hasMany(SnipperFileSK::class,'kode_satker','KodeSatker');
    }

    public function pejabat() {
        return $this->hasMany(SnipperPejabat::class,'kode_satker','KodeSatker')->where('status','1')->orderby('jabatan','asc');
    }

}
