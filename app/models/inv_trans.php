<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class inv_trans extends Model
{
    protected $guarded=[];
    protected $connection = 'farmasi';
    protected $primaryKey = 'Transaction ID';
    protected $table = 'Inventory Transactions';
    const CREATED_AT = 'Transaction Created Date';
const UPDATED_AT = 'Transaction Modified Date';
}
