<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnipperRefSk extends Model
{
    use HasFactory;
    protected $table = 'monira_snipper_refsk';
    public function filesk(){
        return $this->hasOne(SnipperFileSK::class,'jenis','keterangan');
    }

}
