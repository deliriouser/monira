<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMessageSatker extends Model
{
    use HasFactory;
    protected $table = 'monira_data_message_satker';
    protected $guarded = [];

    public function message()
    {
        return $this->hasOne(DataMessage::class,'Id','IdMessage');
    }

}
