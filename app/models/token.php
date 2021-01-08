<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class token extends Model
{
    protected $connection = 'portal';
    protected $guarded = [];
    protected $table = 'rstb_api_token';
    // protected $primaryKey = 'ID';
}
