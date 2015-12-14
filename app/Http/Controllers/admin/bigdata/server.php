<?php

namespace App\Http\Controllers\admin\bigdata;

use App\model\BigData\BgServer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class server extends Controller
{
    private $role_list = array(
        '1'     =>  'namenode',
        '2'     =>  'datanode'
    );
    public function __construct()
    {
        $this->url = '/admin/bgserver';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->input();
        $name = isset($data['hostname']) ? urldecode($data['hostname']) : '';
        $role = isset($data['role']) ? $data['role'] : '';
        $pagesize = 20;
        $query = BgServer::orderBy('id','desc');
        if(!empty($name))
        {
            $query->where('hostname','like','%'.$name.'%');
        }
        if(!empty($role))
        {
            $query->where('role','like','%'.$role.'%');
        }

        $list = $query->paginate($pagesize);

        return view('admin.bg.servers',['data'=>$list,'name'=>$name,'role'=>$role,'url'=>$this->url,'roles'=>$this->role_list]);

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
