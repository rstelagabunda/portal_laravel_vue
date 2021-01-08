<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class tarif_rj extends Model
{
    protected $connection = 'out';
    protected $guarded = [];
    protected $table = 'Tarif_RJ_UGD';
    protected $primaryKey = 'ID';
}
