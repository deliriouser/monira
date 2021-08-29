<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRevisi extends Model
{
    use HasFactory;
    protected $table = 'monira_data_revisi';
    protected $fillable = ['revisi','tahun'];

    public function data_revisi(){
        return $this->hasOne(PaguDipa::class,'Revision','revisi')->selectraw('Revision,sum(amount) as jumlah')->groupby('Revision');
    }

}
