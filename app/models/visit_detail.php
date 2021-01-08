<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class visit_detail extends Model
{
    protected $connection = 'out';
    protected $guarded = [];
    protected $table = 'Visit Details';
    protected $primaryKey = 'ID';
}
