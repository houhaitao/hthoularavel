<?php

namespace App\Http\Controllers\admin;

use App\model\DataGroup;
use App\model\DataGroupManagerRole;
use App\model\DataGroupResource;
use App\model\DataGroupManager;
use App\model\DataGroupRole;
use App\model\DataManager;
use App\model\DataRole;
use App\model\DataType;
use App\model\DataPrivilege;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\admin\privilege;
use Tools\GlobalTools;

class group extends Controller
{
    public function __construct()
    {
        $this->url = '/admin/group';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->input();
        $name = isset($data['name']) ? urldecode($data['name']) : '';
        $pagesize = 20;

        $query = DataGroup::where('status',Config::get('hthou.status_normal'));
        if(!empty($name))
        {
            $query->where('group_name','like',"%".$name."%");
        }
        $query->orderBy('listorder','desc')->orderBy('id','desc');
        $list = $query->paginate($pagesize);
        $roles = DataRole::where('status',Config::get('hthou.status_normal'))->get();
        $priv_ob = new privilege();
        $mk_forward = GlobalTools::make_forward();
        $priv_list = $priv_ob->getTypeData(false);
        return view('admin.groups',['mk_forward'=>$mk_forward,'data'=>$list,'name'=>$name,'url'=>$this->url,'roles'=>$roles,'priv_list'=>$priv_list]);
    }

    public function getMembers($id,Request $request)
    {
        $forward = GlobalTools::get_forward();
        $data = $request->input();
        $mkforward = $data['forward'];
        $name = isset($data['u']) ? $data['u'] : '';
        $query = DataGroupManager::where('group_id',$id);
        if(!empty($name))
        {
            $user = DataManager::where('username',$name)->first();
            if(isset($user->id))
            {
                $uid = $user->id;
            }
            else
            {
                $uid = 0;
            }
            $query->where('manager_id',$uid);
        }

        $res =  $query->orderBy('id','desc')->paginate(50);
        $roles = DataRole::where('status',Config::get('hthou.status_normal'))->get();
        return view('admin.group_members',['mkforward'=>$mkforward,'roles'=>$roles,'name'=>$name,'forward'=>$forward,'groupid'=>$id,'data'=>$res,'url'=>$this->url]);
    }

    public function searchmember(Request $request)
    {

        $data = $request->input();
        if(!isset($data['name']) || empty($data['name']))
        {
            $this->hht_alert('search_message','danger','请填写用户名');
            $this->hht_response_execute();
        }
        $url = $this->url.'/member/'.$data['groupid'].'/?forward='.$data['mkforward'].'&u='.urlencode($data['name']);
        $this->hht_redirect($url);
        $this->hht_response_execute();
    }

    public function getGroupMemberRole($groupid,$managerid)
    {
        $res = DataGroupManagerRole::where('group_id',$groupid)->where('manager_id',$managerid)->get();
        $list = array();
        foreach($res as $r)
        {
            $list[] = $r['role_id'];
        }
        return json_encode($list);
    }

    public function storeGroupMemberRole(Request $request)
    {
        $data = $request->input();
        $groupid = $data['groupid'];
        $managerid = $data['managerid'];
        DataGroupManagerRole::where('group_id',$groupid)->where('manager_id',$managerid)->delete();
        if(isset($data['roles']))
        {
            foreach($data['roles'] as $role_id)
            {
                $gmr = new DataGroupManagerRole();
                $gmr->manager_id = $managerid;
                $gmr->role_id = $role_id;
                $gmr->group_id = $groupid;
                $gmr->save();
            }
        }
        $this->hht_alert_ok('info','组成员角色信息成功');
        $this->hht_response_execute();

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
            $this->hht_alert('add_message','danger','请填写组名称');
            $this->hht_response_execute();
        }
        if(!isset($data['id'])) //添加
        {

            $count = DataGroup::where('group_name',$data['name'])->where('status',Config::get("hthou.status_normal"))->count();
            if($count > 0)
            {
                $this->hht_alert('add_message','danger','您要添加的组已存在，请更换');
                $this->hht_response_execute();
            }
            $group_data = new DataGroup();
            $group_data->group_name = $data['name'];
            $group_data->description = $data['description'];
            $group_data->addtime = time();
            $group_data->listorder = 0;
            $group_data->status = Config::get("hthou.status_normal");
            $group_data->save();
        }
        else //修改
        {
            $group_data = DataGroup::find($data['id']);
            $group_data->group_name = $data['name'];
            $group_data->description = $data['description'];
            $group_data->save();
        }
        $this->hht_alert_ok('info','组信息保存成功');
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
        $info = DataGroup::find($id)->toJson();
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
                $info = DataGroup::find($id);
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
                $info = DataGroup::find($id);
                $info->status=0;
                $info->save();
            }
        }
        $this->hht_alert_ok('info','删除成功');
        $this->hht_response_execute();
    }

    public function ajaxGetGroupResource($group_id)
    {
        $res = DataGroupResource::where('group_id',$group_id)->get();
        $list = array();
        foreach($res as $v)
        {
            $list[]=array($v->resource_type,$v->resource_id);
        }
        return json_encode($list);
    }

    public function storePriv(Request $request)
    {
        $data = $request->input();
        $groupid = $data['id'];
        $resource = $data['priv'];
        DataGroupResource::where('group_id',$groupid)->delete();
        if(is_array($resource))
        {
            foreach($resource as $v)
            {
                $tmp = explode('_',$v);
                $view_type = $tmp[0];
                $view_id = $tmp[1];
                $gr_data = new DataGroupResource();
                $gr_data->resource_type = $view_type;
                $gr_data->resource_id = $view_id;
                $gr_data->group_id = $groupid;
                $gr_data->save();
            }
        }
        $this->hht_alert_ok('info','组权限信息保存成功');
        $this->hht_response_execute();
    }

    public function storeRole(Request $request)
    {
        $data = $request->input();
        $groupid = $data['id'];
        DataGroupRole::where('group_id',$groupid)->delete();
        if(is_array($data['role']))
        {
            foreach($data['role'] as $role_id=>$privs)
            {
                $gr = new DataGroupRole();
                $gr->group_id = $groupid;
                $gr->role_id = $role_id;
                $gr->privilege = json_encode($privs);
                $gr->save();
            }
        }
        $this->hht_alert_ok('info','组角色信息保存成功');
        $this->hht_response_execute();
    }

    public function getAllGroups()
    {
        $query = DataGroup::where('status',Config::get('hthou.status_normal'));
        $res = $query->orderBy('listorder','desc')->orderBy('id','desc')->get();
        $list = array();
        foreach($res as $r)
        {
            $tmp = array(
                'name'      =>  $r->group_name,
                'id'        =>  $r->id,
            );
            $list[] = $tmp;
        }
        return json_encode($list);
    }

    public function getGroupRoleAndPrivilege($id)
    {
        $data = DataGroupRole::where('group_id',$id)->get()->toJson();
        return $data;
    }
}
