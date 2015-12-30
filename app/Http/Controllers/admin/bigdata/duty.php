<?php

namespace App\Http\Controllers\admin\bigdata;

use App\model\BigData\BgDuty;
use App\model\DataType;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class duty extends Controller
{
    private $role_list = array(
        '1'     =>  'namenode-active',
        '3'     =>  'namenode-backup',
        '2'     =>  'datanode'
    );
    public function __construct()
    {
        $this->url = '/admin/bgduty';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->input();
        $name = isset($data['dutyname']) ? urldecode($data['dutyname']) : '';
        $query = BgDuty::orderBy('id','desc');
        $pagesize = 20;
        if(!empty($name))
        {
            $query->where('duty_name','like','%'.$name.'%');
        }
        $list = $query->paginate($pagesize);
        $type_res = DataType::where('top_code','hadoop_op_method')->where('status',Config::get('hthou.status_normal'))->get();
        $type_list = array();
        foreach($type_res as $v)
        {
            $type_list[$v->type_code] = $v->type_name;
        }
        return view('admin.bg.duty',['type_list'=>$type_list,'data'=>$list,'name'=>$name,'url'=>$this->url,'roles'=>$this->role_list]);

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
