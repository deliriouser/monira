<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProfileUser extends Model
{
    use HasFactory;
    protected $table = 'monira_data_profile';
    protected $fillable = ['id', 'address', 'phone', 'email'];

}
