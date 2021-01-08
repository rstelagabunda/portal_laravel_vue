<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class inpatient extends Model
{
    protected $connection = 'in';
    protected $guarded = [];
    protected $table = 'Inpatient';
    protected $primaryKey = 'ID';
    protected $hidden = ['TS'];

    public function Admision()
    {
        return $this->belongsTo('App\Admision');
    }
}
