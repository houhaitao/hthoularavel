<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\model\DataMenu;
use Illuminate\Support\Facades\Config;
use Symfony\Component\VarDumper\Cloner\Data;

class menu extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->url = '/admin/menu';
    }
    public function index(Request $request)
    {
        $data = $request->input();
        $pid = isset($data['pid']) ? $data['pid'] : 0;
        $name = isset($data['name']) ? $data['name'] : '';
        $pagesize = 20;
        if(!empty($pid))
        {
            $backinfo = DataMenu::find($pid);
            $backid = $backinfo->parentid;
        }
        else
        {
            $backid=0;
        }
        $pid = intval($pid);
        $query = DataMenu::where('parentid',$pid);
        $query->where('status',Config::get("hthou.status_normal"));
        if(!empty($name))
        {
            $query->where('myname','like','%'.$name.'%');
        }
        $query->orderBy('listorder','desc')->orderBy('id','desc');
        $list = $query->paginate($pagesize);
        return view('admin.menus',['data'=>$list,'pid'=>$pid,'url'=>$this->url,'backid'=>$backid,'name'=>$name]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();
        if(empty($data['myname']))
        {
            $this->hht_alert('add_menu','danger','请填写菜单名称');
            $this->hht_response_execute();
        }
        if(!isset($data['id'])) //添加
        {
            $menu_data = new DataMenu();
            $menu_data->myname = $data['myname'];
            $menu_data->parentid = $data['parentid'];
            $menu_data->image = $data['image'];
            $menu_data->url = $data['url'];
            $menu_data->target = '';
            $menu_data->isfolder = 0;
            $menu_data->isopen = 0;
            $menu_data->listorder = 0;
            $menu_data->status = Config::get("hthou.status_normal");
            $menu_data->save();
            if(!empty($menu_data->parentid)){
                $pmenu = DataMenu::find($menu_data->parentid);
                $pmenu->isfolder = 1;
                $pmenu->save();
            }

        }
        else //修改
        {
            $menu_data = DataMenu::find($data['id']);
            $menu_data->myname = $data['myname'];
            $menu_data->url = $data['url'];
            $menu_data->image = $data['image'];
            $menu_data->save();
        }
        $this->hht_alert_ok('info','菜单保存成功');
        $this->hht_response_execute();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = DataMenu::find($id)->toJson();
        return $info;
    }

    public function delete(Request $request)
    {
        $data = $request->input();
        if(isset($data['id']) && is_array($data['id']))
        {
            foreach($data['id'] as $id)
            {
                $info = DataMenu::find($id);
                $info->status=0;
                $info->save();
                $pid = $info->parentid;
            }
            if($pid > 0)
            {
                $chilrens = DataMenu::where('parentid',$pid)->where('status',Config::get("hthou.status_normal"))->count();
                if($chilrens <= 0 )
                {
                    $pinfo = DataMenu::find($pid);
                    $pinfo->isfolder=0;
                    $pinfo->save();
                }
            }
        }
        $this->hht_alert_ok('info','删除成功');
        $this->hht_response_execute();
    }

    public function listorder(Request $request)
    {
        $data = $request->input();
        if(isset($data['listorder']) && is_array($data['listorder']))
        {
            foreach($data['listorder'] as $id=>$order)
            {
                $info = DataMenu::find($id);
                $info->listorder=$order;
                $info->save();
            }
        }
        $this->hht_alert_ok('info','排序成功');
        $this->hht_response_execute();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $data = $request->input();
        $url = $this->hht_make_search_url($data,'name');
        $this->hht_redirect($url);
        $this->hht_response_execute();
    }

    public function ajaxMenuTree()
    {
        $tree = array();
        DataMenu::get_menu_tree($tree);
        return json_encode($tree);
    }

    public function ajaxMenuPath($id)
    {
        $path = array();
        DataMenu::get_menu_path($path,$id);
        return json_encode($path);
    }
}
