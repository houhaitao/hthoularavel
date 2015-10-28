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
    public function index()
    {
        //
        $list = DataMenu::all();
        return view('admin.menus',['data'=>$list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.menus');
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
        $menu_data = new DataMenu();
        $menu_data->myname = $data['myname'];
        $menu_data->parentid = $data['parentid'];
        $menu_data->image = '';
        $menu_data->url = $data['url'];
        $menu_data->target = '';
        $menu_data->isfolder = 0;
        $menu_data->isopen = 0;
        $menu_data->listorder = 0;
        $menu_data->status = Config::get("hthou.status_normal");
        if(!isset($data['id'])) //添加
        {
            $menu_data->save();

        }
        else //修改
        {

        }
        $this->hht_alert('add_menu','info','我哈哈哈"还是发顺丰"');
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
