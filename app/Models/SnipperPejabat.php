<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SnipperPejabat extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'monira_snipper_pejabat';
    protected $guarded = [];
    protected $with = ['profile','refJabatan','bnt','barjas'];


    public function profile(){
        return $this->hasOne(SnipperProfile::class,'nip','nip');
    }

    public function bnt(){
        return $this->hasOne(SnipperBNT::class,['nip', 'jabatan'],['nip','jabatan']);
    }

    public function barjas(){
        return $this->hasOne(SnipperCertificate::class,['nip', 'jenis_sertifikat'],['nip','jabatan'])->where('jenis_sertifikat','2');
    }

    public function refJabatan(){
        return $this->hasOne(SnipperRefJabatan::class,'id_jabatan','jabatan');
    }

    public function sertifikasi(){
        return $this->hasMany(SnipperCertificate::class,'nip','nip');
    }

    public function riwayat(){
        return $this->hasMany(SnipperHistory::class,['nip_pejabat', 'kode_satker'],['nip','kode_satker'])->orderby('status_pejabat','desc');
    }
    public function detiljabatan(){
        return $this->hasOne(SnipperHistory::class,['nip_pejabat', 'kode_satker'],['nip','kode_satker']);
    }

}
