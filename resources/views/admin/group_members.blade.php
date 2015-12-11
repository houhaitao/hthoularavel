@extends('admin.main')
@section('title','组成员管理')
@section('content')
    <div class="row">

        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-edit"></i> 搜索</h2>

                    <div class="box-icon">

                        <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="box-content">
                    <form role="form" method="post" action="{{$url}}/searchmember" onsubmit="return r_submit(this)">
                        <div class="col-md-12">
                            <div id="search_message" class="alert alert-info displaynone"></div>
                            <div class="form-group col-md-4">
                                <input type="text" name="name" value="{{$name}}" class="form-control" id="exampleInputEmail1" placeholder="用户名" />
                                <input type="hidden" name="groupid" value="{{$groupid}}"/>
                                <input type="hidden" name="mkforward" value="{{$mkforward}}"/>
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
                    <h2><i class="glyphicon glyphicon-user"></i> 组成员管理</h2>

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
                                <th>用户名</th>
                                <th>昵称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $r)
                            <tr>
                                <td>{{$r->manager->username}}</td>
                                <td>@if(empty($r->manager->nickname))未设置@else{{$r->manager->nickname}}@endif</td>
                                <td class="center">
                                    <a class="btn btn-info priv_mod" id="priv_{{$r->manager_id}}" href="javascript:void(0);">
                                        <i class="glyphicon glyphicon-edit icon-white"></i>
                                        组成员角色
                                    </a>
                                </td>
                            </tr>
                            @endforeach


                            </tbody>
                        </table>
                        <div>
                            <div class="col-md-4">
                                <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                                <input type="button" onclick="self.location='{{$forward}}'" class="btn btn-success" value="返回"/>
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

<!--组成员角色-->
        <div class="modal fade" id="myPrivModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{$url}}/groupmemberrole" name="add_form" method="post"
                          onsubmit="return r_submit(this)">
                        <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                        <input type="hidden" name="managerid" id="priv_id" value="">
                        <input type="hidden" name="groupid" value="{{$groupid}}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h3>修改组成员角色</h3>
                        </div>
                        <div class="modal-body" id="priv_area">
                            <div class="box-content">
                                <div id="myTabContent" class="tab-content">
                                    @foreach($roles as $role)
                                        <input type="checkbox" name="roles[]" value="{{$role->id}}">{{$role->name}}&nbsp;&nbsp;
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                            <input type="submit" value="提交" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
<!--组成员角色结束-->
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
        var type_url = '/admin/group/';
        var group_priv;


        $(document).ready(function () {


            /**
             * 组权限
             */
            $(".priv_mod").click(function(e){
                e.preventDefault();
                $("[name='roles[]']").prop("checked",false);
                var id_str = $(this).attr('id');
                var id = id_str.replace('priv_','');
                $('#priv_id').val(id);
                $.get(type_url+'groupmeberrole/{{$groupid}}/'+id,function(result_res){
                    var roles = JSON.parse(result_res);
                    for(var i=0;i<roles.length;i++)
                    {
                        $("[name='roles[]'][value='"+roles[i]+"']").prop("checked",true);
                    }

                });
                $('#myPrivModal').modal('show');

            });
        });
    </script>

@endsection