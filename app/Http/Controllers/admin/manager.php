<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\model\DataManager;
use Illuminate\Support\Facades\Config;

class manager extends Controller
{
    private $url='/admin/manager';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name='')
    {
        //
        $pagesize = 20;

        $query = DataManager::where('status',Config::get("hthou.status_normal"));
        if(!empty($name))
        {
            $query->where('username',$name);
        }
        $query->orderBy('id','asc');
        $list = $query->paginate($pagesize);
        return view('admin.managers',['data'=>$list,'url'=>$this->url,'name'=>$name]);
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
            if(empty($data['username']))
            {
                $this->hht_alert('add_manager','danger','请填写用户名');
                $this->hht_response_execute();
            }
            if(empty($data['password']))
            {
                $this->hht_alert('add_manager','danger','请填写用密码');
                $this->hht_response_execute();
            }
            if($data['password'] != $data['repassword'])
            {
                $this->hht_alert('add_manager','danger','两次密码输入不一致');
                $this->hht_response_execute();
            }
            $manager_data = new DataManager();
            $userinfo = $manager_data->getUserByUsername($data['username']);
            if($userinfo != false)
            {
                $this->hht_alert('add_manager','danger','该用户名已存在，请更换用户名');
                $this->hht_response_execute();
            }
            $manager_data->username = $data['username'];
            $manager_data->password = $manager_data->password_encode($data['password']);
            $manager_data->nickname = $data['nickname'];
            $manager_data->usertype = $data['usertype'];
            $manager_data->status = Config::get("hthou.status_normal");
            $manager_data->addtime = time();
            $manager_data->save();
        }
        else //修改
        {
            if(!empty($data['password']) && $data['password'] != $data['repassword'])
            {
                $this->hht_alert('add_manager','danger','两次密码输入不一致');
                $this->hht_response_execute();
            }
            $manager_data = DataManager::find($data['id']);
            if(!empty($data['password']))
            {
                $manager_data->password = $manager_data->password_encode($data['password']);
            }
            $manager_data->nickname = $data['nickname'];

            $manager_data->save();
        }
        $this->hht_alert_ok('info','管理员信息保存成功');
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
        $info = DataManager::find($id)->toJson();
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
}
