<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\model\DataResource;
use Illuminate\Support\Facades\Config;
class resource extends Controller
{
    private $url='/admin/resource';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pagesize = 20;
        $query = DataResource::orderBy('listorder','desc');
        $list = $query->paginate($pagesize);
        return view('admin.resources',['data'=>$list,'url'=>$this->url]);
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

        if(!isset($data['id'])) //添加
        {
            if(empty($data['name']))
            {
                $this->hht_alert('add_resource','danger','请填写资源名称');
                $this->hht_response_execute();
            }
            if(empty($data['view_type']))
            {
                $this->hht_alert('add_resource','danger','请填写编码');
                $this->hht_response_execute();
            }
            if(empty($data['res_sql']))
            {
                $this->hht_alert('add_resource','danger','请填写sql');
                $this->hht_response_execute();
            }

            $resource_data = new DataResource();

            $resource_data->name = $data['name'];
            $resource_data->view_type = $data['view_type'];
            $resource_data->res_sql = $data['res_sql'];
            $resource_data->listorder = 0;
            $resource_data->save();
        }
        else //修改
        {
            if(empty($data['name']))
            {
                $this->hht_alert('add_resource','danger','请填写资源名称');
                $this->hht_response_execute();
            }
            if(empty($data['view_type']))
            {
                $this->hht_alert('add_resource','danger','请填写编码');
                $this->hht_response_execute();
            }
            if(empty($data['res_sql']))
            {
                $this->hht_alert('add_resource','danger','请填写sql');
                $this->hht_response_execute();
            }
            $resource_data = DataResource::find($data['id']);
            $resource_data->name = $data['name'];
            $resource_data->view_type = $data['view_type'];
            $resource_data->res_sql = $data['res_sql'];

            $resource_data->save();
        }
        $this->hht_alert_ok('info','数据资源保存成功');
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
        $info = DataResource::find($id)->toJson();
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

    public function delete(Request $request)
    {
        $data = $request->input();
        if(isset($data['id']) && is_array($data['id']))
        {
            foreach($data['id'] as $id)
            {
                $info = DataResource::find($id);
                $info->delete();
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
                $info = DataResource::find($id);
                $info->listorder=$order;
                $info->save();
            }
        }
        $this->hht_alert_ok('info','排序成功');
        $this->hht_response_execute();
    }
}
