@extends('admin.main')
@section('title','组管理')
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
                    <form role="form" method="post" action="{{$url}}/search" onsubmit="return r_submit(this)">
                        <div class="col-md-12">
                            <div class="form-group col-md-4">
                                <input type="text" name="name" value="{{$name}}" class="form-control" id="exampleInputEmail1" placeholder="组名称" />

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
                    <h2><i class="glyphicon glyphicon-user"></i> 组管理</h2>

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
                                <th>排序</th>
                                <th>组名称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $r)
                            <tr>
                                <td><input type="checkbox" name="id[]" class="ck" value="{{$r->id}}"/></td>
                                <td><input size="4" name="listorder[{{$r->id}}]" value="{{$r->listorder}}" type="text"/></td>
                                <td>{{$r->group_name}}</td>
                                <td class="center">
                                    <a class="btn btn-info data_mod" id="mod_{{$r->id}}" href="javascript:void(0);">
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
                                <input type="button" onclick="change_opt('form1','{{$url}}/listorder')" class="btn btn-default" value="排序"/>&nbsp;
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
        });
    </script>

@endsection