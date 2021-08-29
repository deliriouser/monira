<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnipperProfile extends Model
{
    use HasFactory;
    protected $table = 'monira_snipper_profile';
    protected $guarded = [];

    public function bnt(){
        return $this->hasOne(SnipperBNT::class,'nip','nip');
    }

    public function barjas(){
        return $this->hasOne(SnipperCertificate::class,'nip','nip')->where('jenis_sertifikat','2');
    }

}
