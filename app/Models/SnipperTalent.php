<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnipperTalent extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'monira_snipper_talent';
    protected $guarded = [];
    protected $with = ['profile','refJabatan','certificate'];
    public $primaryKey = 'pejabat_id';

    public function profile(){
        return $this->hasOne(SnipperProfile::class,'nip','nip');
    }
    public function refJabatan(){
        return $this->hasOne(SnipperRefJabatan::class,'id_jabatan','jabatan');
    }
    public function certificate(){
        return $this->hasOne(SnipperCertificate::class,['nip','jenis_sertifikat'],['nip','jabatan']);
    }

}
