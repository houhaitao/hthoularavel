<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class DataManager extends Model
{
    //
    public $timestamps = false;
    protected $table = 'manager';

    public function getUserByUsername($username)
    {
        $res = self::where("username",$username)->where('status',\Illuminate\Support\Facades\Config::get("hthou.status_normal"))->get();
        $info = false;
        foreach($res as $r)
        {
            $info = $r;
        }
        return $info;
    }

    public function password_encode($password)
    {
        $str = '';
        for($i=0;$i<strlen($password);$i++)
        {
            $str .= chr((ord($password[$i])+$i)%128);
        }
        return md5(sha1($str));
    }
}
