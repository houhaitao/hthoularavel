<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\model\DataPrivilege;
use App\model\DataType;
use Illuminate\Support\Facades\Config;

class privilege extends Controller
{
    private $priv_type_code = 'priv';
    public function __construct()
    {
        $this->url = '/admin/privilege';
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
        $typecode = isset($data['typecode']) ? $data['typecode'] : '';
        $code = isset($data['code']) ? $data['code'] : '';
        $pagesize = 20;
        $typelist = array();
        $type_res = DataType::where('parent_code',$this->priv_type_code)->where("status",Config::get("hthou.status_normal"))->orderBy('listorder','desc')->orderBy('id','desc')->get();
        foreach($type_res as $type_info)
        {
            $typelist[$type_info->type_code] = $type_info->type_name;
        }
        $query = DataPrivilege::where('status',Config::get('hthou.status_normal'));
        if(!empty($typecode))
        {
            $query->where('type_code',$typecode);
        }
        if(!empty($code))
        {
            $query->where('code','like',"%".$code."%");
        }
        $query->orderBy('listorder','desc')->orderBy('id','desc');
        $list = $query->paginate($pagesize);
        return view('admin.privileges',['data'=>$list,'typecode'=>$typecode,'url'=>$this->url,'code'=>$code,'typelist'=>$typelist]);
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
            $this->hht_alert('add_message','danger','请填写权限元名称');
            $this->hht_response_execute();
        }
        if(!isset($data['id'])) //添加
        {
            if(empty($data['code']))
            {
                $this->hht_alert('add_message','danger','请填写权限元编号');
                $this->hht_response_execute();
            }

            $count = DataPrivilege::where('code',$data['code'])->where('status',Config::get("hthou.status_normal"))->count();
            if($count > 0)
            {
                $this->hht_alert('add_message','danger','权限元编号已存在，请更换');
                $this->hht_response_execute();
            }
            $privilege_data = new DataPrivilege();
            $privilege_data->name = $data['name'];
            $privilege_data->code = $data['code'];
            $privilege_data->type_code = $data['type_code'];
            $privilege_data->rel_key = '';
            $privilege_data->description = $data['description'];
            $privilege_data->addtime = time();
            $privilege_data->listorder = 0;
            $privilege_data->status = Config::get("hthou.status_normal");
            $privilege_data->save();
        }
        else //修改
        {
            $privilege_data = DataPrivilege::find($data['id']);
            $privilege_data->name = $data['name'];
            $privilege_data->type_code = $data['type_code'];
            $privilege_data->description = $data['description'];
            $privilege_data->save();
        }
        $this->hht_alert_ok('info','权限元保存成功');
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
        $info = DataPrivilege::find($id)->toJson();
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
        $url = $this->hht_make_search_url($data);
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
                $info = DataPrivilege::find($id);
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
                $info = DataPrivilege::find($id);
                $info->status=0;
                $info->save();
            }
        }
        $this->hht_alert_ok('info','删除成功');
        $this->hht_response_execute();
    }
}
