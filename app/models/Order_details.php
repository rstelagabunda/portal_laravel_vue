<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Order_details extends Model
{
    protected $guarded=[];
    protected $connection = 'farmasi';
    protected $primaryKey = 'ID';
    protected $table = 'Order Details';
}
