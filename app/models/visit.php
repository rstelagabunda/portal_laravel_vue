<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class visit extends Model
{
    protected $connection = 'out';
    protected $guarded = [];
    protected $table = 'Visit';
    protected $primaryKey = 'ID';
}
