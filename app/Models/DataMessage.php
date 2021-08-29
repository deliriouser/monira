<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMessage extends Model
{
    use HasFactory;
    protected $table = 'monira_data_message';
    protected $guarded = [];

    public function attachment()
    {
        return $this->hasMany(DataMessageAttachment::class,'IdMessage','Id');
    }
}
