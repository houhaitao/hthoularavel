<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class DataGroupManager extends Model
{
    //
    public $timestamps = false;
    protected $table = 'group_manager';

    public function manager()
    {
        return $this->hasOne('App\model\DataManager','id','manager_id');
    }
}
