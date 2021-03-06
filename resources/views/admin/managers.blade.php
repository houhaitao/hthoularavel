@extends('admin.main')
@section('title','管理员管理')
@section('content')

    <div class="row">

        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-edit"></i> 搜索</h2>

                    <div class="box-icon">

                        <a href="#" class="btn btn-minimize btn-round btn-default"><i
                                    class="glyphicon glyphicon-chevron-up"></i></a>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="box-content">
                    <form role="form" method="post" action="{{$url}}/search" onsubmit="return r_submit(this)">
                        <div class="col-md-12">
                            <div class="form-group col-md-4">
                                <input type="text" name="name" value="{{$name}}" class="form-control"
                                       id="exampleInputEmail1" placeholder="用户名" />

                                <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputPassword1">&nbsp;</label>
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>

        <!--/span-->

    </div><!--/row-->

    <div class="row">

        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-user"></i> 管理员管理</h2>

                    <div class="box-icon">

                        <a href="#" class="btn btn-minimize btn-round btn-default"><i
                                    class="glyphicon glyphicon-chevron-up"></i></a>

                    </div>
                </div>
                <div class="box-content">
                    <div class="alert alert-info displaynone"></div>
                    <form action="" name="form1" method="post"  onsubmit="return r_submit(this)">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="checkall" children="ck"/></th>
                                <th>用户名</th>
                                <th>昵称</th>
                                <th>用户类型</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $r)
                                <tr>
                                    <td><input type="checkbox" name="id[]" class="ck" value="{{$r->id}}"/></td>
                                    <td>{{$r->username}}</td>
                                    <td class="center">@if(!empty($r->nickname)){{$r->nickname}}@else空@endif</td>
                                    <td class="center">@if($r->usertype=='0')普通管理员@else超级管理员@endif</td>
                                    <td class="center">
                                        <a class="btn btn-info manager_mod" id="mod_{{$r->id}}" href="javascript:void(0);">
                                            <i class="glyphicon glyphicon-edit icon-white"></i>
                                            修改
                                        </a>
                                    </td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                        <div>
                            <div class="col-md-4">
                                <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                                <input type="button" onclick="change_opt('form1','{{$url}}/delete')" class="btn btn-danger" value="删除"/>&nbsp;
                                <input type="button" id="op_add_form_manager" class="btn btn-success" value="添加"/>

                            </div>
                            <div class="col-md-8 no-padding no-margin myright">
                                {!! $data->render() !!}
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{Config::get('hthou.local_path')}}admin/manager" name="add_form" method="post"
                          onsubmit="return r_submit(this)">
                        <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h3>添加管理员</h3>
                        </div>
                        <div class="modal-body">
                            <div id="add_manager" class="alert alert-info displaynone"></div>
                            <div class="form-group col-md-12 myusername">
                                <input type="text" class="form-control" id="form_username" name="username"
                                       placeholder="用户名">
                            </div>
                            <div class="form-group col-md-12">
                                <input type="password" class="form-control" id="form_password" name="password"
                                       placeholder="用户密码">
                            </div>
                            <div class="form-group col-md-12">
                                <input type="password" class="form-control" id="form_repassword" name="repassword"
                                       placeholder="确认密码">
                            </div>
                            <div class="form-group col-md-12">
                                <input type="text" class="form-control" id="form_nickname" name="nickname"
                                       placeholder="昵称">
                            </div>
                            <div class="form-group col-md-12 myusertype">
                                <div class="controls">
                                    <select id="form_usertype" class="form-control" name="usertype">
                                        <option value="0">普通管理员</option>
                                        <option value="99">超级管理员</option>
                                    </select>
                                </div>
                            </div>
                            <label class="col-md-12 super_none">角色</label>
                            <div class="form-group col-md-12 super_none">

                                @foreach($role_list as $r)
                                    <input type="checkbox" class="roles" name="roles[]" value="{{$r->id}}">{{$r->name}}&nbsp;
                                @endforeach
                            </div>
                            <label class="col-md-12 super_none">组</label>
                            <div class="form-group col-md-12 super_none">

                                @foreach($group_list as $g)
                                    <input type="checkbox" class="groups" name="groups[]" value="{{$g->id}}">{{$g->group_name}}&nbsp;
                                @endforeach
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                            <input type="submit" value="提交" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="mydialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">


                    <div class="modal-body">
                        <div id="my_dd" class="alert alert-info"></div>

                        <div class="clear"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" onclick="self.location=self.location" id="button" value="确定" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </div>


        <!-- content ends -->
    </div><!--/#content.col-md-0-->
    <script>
        var manager_url = '/admin/manager/';
        $(document).ready(function () {
            /**
             * 管理员管理
             */
            $("#op_add_form_manager").click(function(e){
                e.preventDefault();
                $("#form_password").val("");
                $("#form_repassword").val("");
                $("#form_nickname").val("");
                if($("#form_id").val() != undefined)
                {
                    $("#form_id").remove();
                }
                $(".myusername").html("<input type=\"text\" class=\"form-control\" id=\"form_username\" name=\"username\" placeholder=\"用户名\">");
                var select_html = "<div class=\"controls\">";
                select_html += "<select id=\"form_usertype\" class=\"form-control\" name=\"usertype\">";
                select_html += "<option value=\"0\">普通管理员<\/option>";
                select_html += "<option value=\"99\">超级管理员</option></select></div>";
                $(".myusertype").html(select_html);
                $("#add_manager").addClass("displaynone");
                $("#form_usertype").change(function(e){
                    if($(this).val()=='99')
                    {
                        $(".super_none").hide();
                    }
                    else
                    {
                        $(".super_none").show();
                    }
                });
                $(".roles").prop("checked",false);
                $(".groups").prop("checked",false);
                $('#myModal').modal('show');

            });

            $("#form_usertype").change(function(e){
                if($(this).val()=='99')
                {
                    $(".super_none").hide();
                }
                else
                {
                    $(".super_none").show();
                }
            });

            $(".manager_mod").click(function(e){
                var id_str = $(this).attr('id');
                var id = id_str.replace('mod_','');
                var url = manager_url+id;
                $(".roles").prop("checked",false);
                $(".groups").prop("checked",false);
                $.get(url, function(result){
                    var myobj = JSON.parse(result);
                    $(".myusername").html(myobj.username);
                    $("#form_nickname").val(myobj.nickname);
                    var usertype = myobj.usertype=='0' ? "普通管理员" : "超级管理员";
                    $(".myusertype").html(usertype);

                    if($("#form_id").val() == undefined)
                    {
                        $("#form_password").after("<input type=\"hidden\" name=\"id\" id=\"form_id\" value=\""+myobj.id+"\">");
                    }
                    else
                    {
                        $("#form_id").val(id);
                    }
                    if(myobj.usertype=='99')
                    {
                        $(".super_none").hide();
                    }
                    else
                    {
                        $(".super_none").show();
                        var roles = JSON.parse(myobj.roles);
                        var groups = JSON.parse(myobj.groups);
                        var i;
                        for(i=0;i<roles.length;i++)
                        {
                            $(".roles[value='"+roles[i]+"']").prop("checked",true);
                        }
                        for(i=0;i<groups.length;i++)
                        {
                            $(".groups[value='"+groups[i]+"']").prop("checked",true);
                        }
                    }
                    $("#add_manager").addClass("displaynone");
                    $('#myModal').modal('show');

                });
            });

        });
    </script>

@endsection