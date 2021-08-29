<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKomitmen extends Model
{
    use HasFactory;
    protected $table = 'monira_data_komitmen';
    protected $guarded = [];
    protected $primaryKey = 'Id';


}
