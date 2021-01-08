<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $guarded=[];
    protected $connection = 'farmasi';
    protected $primaryKey = 'Order ID';
    protected $table = 'Orders';
    protected $hidden = ['upsize_ts'];

}
