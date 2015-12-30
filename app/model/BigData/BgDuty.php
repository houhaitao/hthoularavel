<?php

namespace App\model\BigData;

use Illuminate\Database\Eloquent\Model;

class BgDuty extends Model
{
    //
    //
    public $timestamps = false;
    protected $table = 'bg_duty';

    public function getDutyRolesAttribute($value)
    {
        return (array)json_decode($value);
    }
}
