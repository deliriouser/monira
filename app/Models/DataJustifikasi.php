<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataJustifikasi extends Model
{
    use HasFactory;
    protected $table = 'monira_data_justifikasi';
    protected $guarded = [];
    protected $primaryKey = 'IdIncrement';

}
