<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnipperCertificate extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'monira_snipper_file_certficate';
    protected $guarded = [];
    public $primaryKey = 'sertifikat_id';
    protected $with = ['sertifikat'];

    public function sertifikat(){
            return $this->hasOne(SnipperRefJabatan::class,'id_jabatan','jenis_sertifikat');
        }
}
