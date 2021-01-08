<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class tblOrderType extends Model
{
    protected $guarded=[];
    protected $connection = 'farmasi';
    protected $primaryKey = 'OrderTypeID';
    protected $table = 'tblOrderType';
    protected $hidden = ['upsize_ts'];
}
