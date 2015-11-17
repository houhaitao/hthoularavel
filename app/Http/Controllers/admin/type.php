<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\model\DataType;
use Illuminate\Support\Facades\Config;
class type extends Controller
{
    public function __construct()
    {
        $this->url = '/admin/type';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data = $request->input();
        $pid = isset($data['pid']) ? $data['pid'] : '';
        $name = isset($data['name']) ? $data['name'] : '';
        $pagesize = 20;
        if(!empty($pid))
        {
            $backinfo = DataType::where('type_code',$pid)->first();
            $backid = $backinfo->parent_code;
        }
        else
        {
            $backid='';
        }
        $query = DataType::where('parent_code',$pid);
        $query->where('status',Config::get("hthou.status_normal"));
        if(!empty($name))
        {
            $query->where('type_name','like','%'.$name.'%');
        }
        $query->orderBy('listorder','desc')->orderBy('id','desc');
        $list = $query->paginate($pagesize);
        return view('admin.types',['data'=>$list,'pid'=>$pid,'url'=>$this->url,'backid'=>$backid,'name'=>$name]);
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
        //
        $data = $request->input();
        if(empty($data['type_name']))
        {
            $this->hht_alert('add_message','danger','请填写分类名称');
            $this->hht_response_execute();
        }
        if(!isset($data['id'])) //添加
        {
            $parent_code = $data['parent_code'];
            $top_code = '';
            if(empty($data['type_code']))
            {
                $this->hht_alert('add_message','danger','请填写分类编号');
                $this->hht_response_execute();
            }
            if(!empty($parent_code))
            {
                $backinfo = DataType::where('type_code',$parent_code)->where('status',Config::get("hthou.status_normal"))->first();
                $top_code = $backinfo->top_code;
            }
            $count = DataType::where('type_code',$data['type_code'])->where('status',Config::get("hthou.status_normal"))->count();
            if($count > 0)
            {
                $this->hht_alert('add_message','danger','分类编号已存在，请更换');
                $this->hht_response_execute();
            }
            $type_data = new DataType();
            $type_data->type_name = $data['type_name'];
            $type_data->type_code = $data['type_code'];
            $type_data->alias = $data['alias'];
            $type_data->parent_code = $parent_code;
            $type_data->top_code = $top_code;
            $type_data->addtime = time();
            $type_data->listorder = 0;
            $type_data->status = Config::get("hthou.status_normal");
            $type_data->save();
        }
        else //修改
        {
            $type_data = DataType::find($data['id']);
            $type_data->type_name = $data['type_name'];
            $type_data->alias = $data['alias'];
            $type_data->save();
        }
        $this->hht_alert_ok('info','分类保存成功');
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
        //
        $info = DataType::find($id)->toJson();
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

    public function search(Request $request)
    {
        $data = $request->input();
        $url = $this->hht_make_search_url($data,'name');
        $this->hht_redirect($url);
        $this->hht_response_execute();
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

    public function listorder(Request $request)
    {
        $data = $request->input();
        if(isset($data['listorder']) && is_array($data['listorder']))
        {
            foreach($data['listorder'] as $id=>$order)
            {
                $info = DataType::find($id);
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
                $info = DataType::find($id);
                $info->status=0;
                $info->save();
            }
        }
        $this->hht_alert_ok('info','删除成功');
        $this->hht_response_execute();
    }
}
