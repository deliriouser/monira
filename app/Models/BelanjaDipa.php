<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BelanjaDipa extends Model
{
    use \Awobaz\Compoships\Compoships;

    protected $table = 'monira_data_belanja';
    protected $guarded = [];

    public function isCovid(){
        return $this->hasone(RefAkun::class,'KdAkun','Akun')->where('isCovid','1');
    }

}
