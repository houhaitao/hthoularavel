@extends('admin.main')
@section('title','组成员管理')
@section('content')


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
                                <th><input type="checkbox" id="checkall" children="ck"/></th>
                                <th>用户名</th>
                                <th>昵称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $r)
                            <tr>
                                <td><input type="checkbox" name="id[]" class="ck" value="{{$r->id}}"/></td>
                                <td>{{$r->manager->username}}</td>
                                <td>@if(empty($r->manager->nickname))未设置@else{{$r->manager->nickname}}@endif</td>
                                <td class="center">
                                    <a class="btn btn-info priv_mod" id="priv_{{$r->id}}" href="javascript:void(0);">
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
                                <input type="button" onclick="change_opt('form1','{{$url}}/delete')" class="btn btn-danger" value="删除"/>&nbsp;
                                <input type="button" id="op_add_form" class="btn btn-success" value="添加"/>
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
                    <form action="{{$url}}" name="add_form" method="post"
                          onsubmit="return r_submit(this)">
                        <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h3>添加组信息</h3>
                    </div>
                    <div class="modal-body">
                        <div id="add_message" class="alert alert-info displaynone"></div>
                        <div class="form-group col-md-12 typecode">
                            <input type="text" class="form-control" id="form_name" name="name"
                                   placeholder="组名称">
                        </div>

                        <div class="form-group col-md-12">
                            <textarea name="description" class="form-control" id="form_description" rows="5" placeholder="描述信息"></textarea>

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
<!--组权限-->
        <div class="modal fade" id="myPrivModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{$url}}/priv" name="add_form" method="post"
                          onsubmit="return r_submit(this)">
                        <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                        <input type="hidden" name="id" id="priv_id" value="">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h3>修改组权限</h3>
                        </div>
                        <div class="modal-body" id="priv_area">
                            <div class="box-content">
                                <ul class="nav nav-tabs priv_tab" id="myTab">

                                </ul>

                                <div id="myTabContent" class="tab-content priv_content">
                                    <img src="/img/ajax-loaders/ajax-loader-6.gif"
                                         title="/img/ajax-loaders/ajax-loader-6.gif">
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
<!--组权限结束-->
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

        function make_tree(data,mytype)
        {
            var len = data.length;
            var i=0;
            var html = '<div class="hht-tree">';
            var myinfo;
            var mylevelmore = false; //本层级下是否有节点有子节点
            for(i=0;i<len;i++)
            {
                myinfo = data[i];
                if(myinfo.child.length > 0)
                {
                    mylevelmore = true;
                }

            }
            //如果均没有子节点
            if(mylevelmore === false)
            {
                for(i=0;i<len;i++)
                {
                    myinfo = data[i];
                    html += '<input type="checkbox" class="priv_ck" id="'+mytype+'_'+myinfo.info.id+'" name="priv[]" value="'+mytype+'_'+myinfo.info.id+'">'+myinfo.info.title+'&nbsp;';

                }
            }
            else
            {
                for(i=0;i<len;i++)
                {
                    myinfo = data[i];
                    html += '<div><input type="checkbox" class="priv_ck" id="'+mytype+'_'+myinfo.info.id+'" name="priv[]" value="'+mytype+'_'+myinfo.info.id+'"><b>'+myinfo.info.title+'</b>';
                    if(myinfo.child.length >0 )
                    {
                        html += make_tree(myinfo.child, mytype);
                    }
                    html += '</div>';
                }
            }
            html += '</div>';
            return html;
        }

        function make_priv_tree(data)
        {
            var str='';
            var len = data.length;
            var i;
            var act;
            var myinfo;
            for(i=0;i<len;i++)
            {
                myinfo = data[i];
                act = i==0 ? 'active' : '';
                str += '<div class="tab-pane '+act+'" id="'+myinfo.info.view_type+'">';
                str += make_tree(myinfo.data,myinfo.info.view_type);
                str += '</div>';
            }
            return str;
        }
        $(document).ready(function () {
            /**
             * 分类管理
             */
            $("#op_add_form").click(function(e){
                e.preventDefault();
                $("#form_name").val("");
                $("#form_description").val("");
                if($("#form_id").val() != undefined)
                {
                    $("#form_id").remove();
                }

                $("#add_resource").addClass("displaynone");
                $('#myModal').modal('show');

            });

            $(".data_mod").click(function(e){
                var id_str = $(this).attr('id');
                var id = id_str.replace('mod_','');
                var url = type_url+id;
                $.get(url, function(result){
                    var myobj = JSON.parse(result);
                    $("#form_name").val(myobj.group_name);
                    $("#form_description").val(myobj.description);
                    if($("#form_id").val() == undefined)
                    {
                        $("#form_description").after("<input type=\"hidden\" name=\"id\" id=\"form_id\" value=\""+myobj.id+"\">");
                    }
                    else
                    {
                        $("#form_id").val(id);
                    }
                    $("#add_message").addClass("displaynone");
                    $('#myModal').modal('show');

                });
            });

            /**
             * 组权限
             */
            $(".priv_mod").click(function(e){
                e.preventDefault();
                var id_str = $(this).attr('id');
                var id = id_str.replace('priv_','');
                $('#priv_id').val(id);
                $.get(type_url+'groupres/'+id,function(result_res){
                    group_priv = JSON.parse(result_res);
                    $.get('/admin/resource/restree',function(result){
                        var myobj = JSON.parse(result);
                        var myobj_len = myobj.length;
                        var i,j;
                        var act_class;
                        var tab_str = '';
                        var inner_str = '';
                        for(i=0;i<myobj_len;i++)
                        {
                            if(i==0)
                            {
                                act_class= 'class="active"';
                            }
                            else
                            {
                                act_class='';
                            }
                            tab_str += '<li '+act_class+'><a href="#'+myobj[i].info.view_type+'">'+myobj[i].info.name+'</a></li>';
                            $(".priv_tab").html(tab_str);
                            inner_str = make_priv_tree(myobj);
                            $(".priv_content").html(inner_str);

                        }
                        $('.nav-tabs a').click(function (e) {
                            e.preventDefault()
                            $(this).tab('show')
                        })
                        $('.priv_ck').click(function(e){
                            if($(this).is(':checked')==true)
                            {
                                $(this).parent().parents().children('.priv_ck').prop("checked",true);
                            }
                        });
                        for(i=0;i<group_priv.length;i++)
                        {
                            $('#'+group_priv[i][0]+'_'+group_priv[i][1]).prop("checked",true);
                            $('#'+group_priv[i][0]+'_'+group_priv[i][1]).parent().parents().children('.priv_ck').prop("checked",true);
                        }

                    });

                });



                $('#myPrivModal').modal('show');

            });
        });
    </script>

@endsection