<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRpdmp extends Model
{
    use HasFactory;
    protected $table = 'monira_data_mp_rpd';
    protected $guarded = [];

    public function ketAkun(){
        return $this->hasOne(RefAkun::class,'KdAkun','akun');
    }


}
