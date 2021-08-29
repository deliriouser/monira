<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnipperHistory extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'monira_snipper_history';
    protected $guarded = [];
    protected $with = ['jabatan','satker'];
    public $primaryKey = 'detil_id';


    public function jabatan(){
            return $this->hasOne(SnipperRefJabatan::class,'id_jabatan','jabatan_id');
        }
    public function satker(){
            return $this->hasOne(DataProfileSatker::class,'KodeSatker','kode_satker');
        }

}
