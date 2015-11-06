<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class DataMenu extends Model
{
    public $timestamps = false;
    protected $table = 'menu';

    static public function get_menu_tree(&$list,$pid=0)
    {
        $query = self::where('parentid',$pid);
        $query->where('status',\Illuminate\Support\Facades\Config::get("hthou.status_normal"));
        $res = $query->orderBy('listorder','desc')->orderBy('id','desc')->get();
        $i = 0;
        foreach($res as $r)
        {
            $tmp = array(
                'myname'    =>  $r->myname,
                'image'     =>  $r->image,
                'url'       =>  $r->url,
                'isfolder'  =>  $r->isfolder,
                'id'        =>  $r->id
            );
            $list[$i] = array();
            $list[$i]['info'] = $tmp;
            if($r->isfolder=='1')
            {
                $children = array();
                self::get_menu_tree($children,$r->id);
                if(!is_array($children) || sizeof($children)<=0)
                {
                    $list[$i]['c'] = '1';
                }
                else
                {
                    $list[$i]['c'] = $children;
                }
            }
            else
            {
                $list[$i]['c'] = '1';
            }
            $i++;
        }
    }

    static public function get_menu_path(&$list,$id)
    {
        $id = intval($id);
        if($id > 0)
        {
            $menu = self::find($id);
            $status = \Illuminate\Support\Facades\Config::get("hthou.status_normal");
            if($menu->status == $status)
            {
                if($menu->parentid > 0)
                {
                    self::get_menu_path($list,$menu->parentid);
                }
                array_push($list,$id);
            }
        }

    }

}
