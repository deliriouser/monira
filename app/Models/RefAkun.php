<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefAkun extends Model
{
    use HasFactory;
    protected $table = 'monira_ref_akun';

    public function pagu_covid(){
        return $this->hasMany(PaguDipa::class,'Akun','KdAkun')->where('IsActive','1');
    }

}
