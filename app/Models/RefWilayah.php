<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefWilayah extends Model
{
    use HasFactory;
    protected $table = 'monira_ref_wilayah';

    public function satker(){
        return $this->hasMany(DataProfileSatker::class,'KodeWilayah','KodeWilayah');
    }

}
