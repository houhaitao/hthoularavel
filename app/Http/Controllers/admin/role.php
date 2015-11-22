<?php

namespace App\Http\Controllers\admin;

use App\model\DataRole;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class role extends Controller
{
    public function __construct()
    {
        $this->url = '/admin/role';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->input();
        $name = isset($data['name']) ? $data['name'] : '';
        $pagesize = 20;

        $query = DataRole::where('status',Config::get('hthou.status_normal'));
        if(!empty($name))
        {
            $query->where('name','like',"%".$name."%");
        }
        $query->orderBy('listorder','desc')->orderBy('id','desc');
        $list = $query->paginate($pagesize);
        return view('admin.roles',['data'=>$list,'name'=>$name,'url'=>$this->url]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if(empty($data['name']))
        {
            $this->hht_alert('add_message','danger','请填写角色名称');
            $this->hht_response_execute();
        }
        if(!isset($data['id'])) //添加
        {
            $count = DataRole::where('name',$data['name'])->where('status',Config::get("hthou.status_normal"))->count();
            if($count > 0)
            {
                $this->hht_alert('add_message','danger','您要添加的角色已存在，请更换');
                $this->hht_response_execute();
            }
            $role_data = new DataRole();
            $role_data->name = $data['name'];
            $role_data->privilege = '';
            $role_data->description = $data['description'];
            $role_data->addtime = time();
            $role_data->listorder = 0;
            $role_data->status = Config::get("hthou.status_normal");
            $role_data->save();
        }
        else //修改
        {
            $role_data = DataRole::find($data['id']);
            $role_data->name = $data['name'];
            $role_data->description = $data['description'];
            $role_data->save();
        }
        $this->hht_alert_ok('info','角色信息保存成功');
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
        $info = DataRole::find($id)->toJson();
        return $info;
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

    public function listorder(Request $request)
    {
        $data = $request->input();
        if(isset($data['listorder']) && is_array($data['listorder']))
        {
            foreach($data['listorder'] as $id=>$order)
            {
                $info = DataRole::find($id);
                $info->listorder=$order;
                $info->save();
            }
        }
        $this->hht_alert_ok('info','排序成功');
        $this->hht_response_execute();
    }

    public function delete(Request $request)
    {
        $data = $request->input();
        if(isset($data['id']) && is_array($data['id']))
        {
            foreach($data['id'] as $id)
            {
                $info = DataRole::find($id);
                $info->status=0;
                $info->save();
            }
        }
        $this->hht_alert_ok('info','删除成功');
        $this->hht_response_execute();
    }
}
