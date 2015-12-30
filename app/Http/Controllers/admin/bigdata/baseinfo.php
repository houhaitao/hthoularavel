<?php

namespace App\Http\Controllers\admin\bigdata;

use App\model\BigData\BgBaseInfo;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class baseinfo extends Controller
{
    public function __construct()
    {
        $this->url = '/admin/bgbaseinfo';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list = BgBaseInfo::all();
        return view('admin.bg.baseinfo',['data'=>$list,'url'=>$this->url]);

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
        if(isset($data['code'])) //添加
        {
            if(empty($data['code']))
            {
                $this->hht_alert('add_message','danger','请填写配置的code');
                $this->hht_response_execute();
            }
            if(empty($data['title']))
            {
                $this->hht_alert('add_message','danger','请填写配置的title');
                $this->hht_response_execute();
            }
            if(empty($data['value']))
            {
                $this->hht_alert('add_message','danger','请填写配置的value');
                $this->hht_response_execute();
            }
            if(BgBaseInfo::check_code_exists($data['code'])===true)
            {
                $this->hht_alert('add_message','danger','您添加的code已存在，请更换');
                $this->hht_response_execute();
            }
            $info = new BgBaseInfo();
            $info->code = $data['code'];
            $info->titlevalue = $data['title'];
            $info->datavalue = $data['value'];
            $info->save();

        }
        else
        {
            foreach($data['value'] as $k=>$v)
            {
                $info = BgBaseInfo::find($k);
                $info->datavalue = $v;
                $info->save();
            }
        }
        $this->hht_alert_ok('info','配置信息保存成功');
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

    public function delete(Request $request)
    {
        $data = $request->input();
        if(isset($data['id']) && is_array($data['id']))
        {
            foreach($data['id'] as $id)
            {
                $info = BgBaseInfo::find($id);
                $info->delete();
            }
        }
        $this->hht_alert_ok('info','删除成功');
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
}
