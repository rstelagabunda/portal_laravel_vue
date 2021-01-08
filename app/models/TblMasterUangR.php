<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class TblMasterUangR extends Model
{
    protected $guarded=[];
    protected $connection = 'farmasi';
    protected $primaryKey = 'IDuangR';
    protected $table = 'TblMasterUangR';
    
}
