<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataPrognosa extends Model
{
    use HasFactory;
    protected $table = 'monira_data_prognosa';
    protected $guarded = [];
    protected $primaryKey = 'IdIncrement';

    public function mingguan(){
        return $this->hasMany(DataPrognosa::class, "BulanChild", "Bulan");
    }

}
