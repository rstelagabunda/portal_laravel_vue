<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Admision extends Model
{
    protected $guarded=[];
    protected $connection = 'master';
    protected $primaryKey = 'ID';
    protected $table = 'Admision';
    protected $hidden = ['TS'];
}
