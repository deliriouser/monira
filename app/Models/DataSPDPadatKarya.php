<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSPDPadatKarya extends Model
{
    use HasFactory;
    protected $table   = 'monira_data_pakar_sppd';
    protected $guarded = [];
    public $primaryKey = 'idtable';


}
