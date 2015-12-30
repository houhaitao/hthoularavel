<?php

namespace App\model\BigData;

use Illuminate\Database\Eloquent\Model;

class BgBaseInfo extends Model
{
    //
    public $timestamps = false;
    protected $table = 'bg_base_info';

    public static function check_code_exists($code)
    {
        $res = self::where("code",$code)->get();
        $info = false;
        foreach($res as $r)
        {
            $info = $r;
        }
        return $info===false ? false : true;
    }
}
