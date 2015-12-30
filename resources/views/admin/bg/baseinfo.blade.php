@extends('admin.main')
@section('title','服务器端基本信息管理')
@section('content')


    <div class="row">

        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-user"></i> 服务器端基本信息管理</h2>

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
                                <th>code</th>
                                <th>title</th>
                                <th>value</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $r)
                                <tr>
                                    <td><input type="checkbox" name="id[]" class="ck" value="{{$r->id}}"/></td>
                                    <td>{{$r->code}}</td>
                                    <td>{{$r->titlevalue}}</td>
                                    <td><input type="text" name="value[{{$r->id}}]" value="{{$r->datavalue}}"/> </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div>
                            <div class="col-md-4">
                                <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                                <input type="button" onclick="change_opt('form1','{{$url}}/delete')" class="btn btn-danger" value="删除"/>&nbsp;
                                <input type="button" onclick="change_opt('form1','{{$url}}')" class="btn btn-default" value="修改">&nbsp;
                                <input type="button" id="op_add_form" class="btn btn-success" value="添加"/>

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
                            <h3>添加配置信息</h3>
                        </div>
                        <div class="modal-body">
                            <div id="add_message" class="alert alert-info displaynone"></div>
                            <div class="form-group col-md-12 typecode">
                                <input type="text" class="form-control" id="form_code" name="code"
                                       placeholder="code">
                            </div>
                            <div class="form-group col-md-12 typecode">
                                <input type="text" class="form-control" id="form_title" name="title"
                                       placeholder="title">
                            </div>
                            <div class="form-group col-md-12 typecode">
                                <input type="text" class="form-control" id="form_value" name="value"
                                       placeholder="value">
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
        var type_url = '/admin/bgserver/';
        $(document).ready(function () {
            $(".zc_mod").click(function(){
                var id_str = $(this).attr('id');
                var id = id_str.replace('zc_','');
                var url = type_url+"mknormal/?id="+id;
                $.get(url, function(result){
                    alert('操作成功');
                    self.location = self.location;
                });
            });

            $("#op_add_form").click(function(e){
                e.preventDefault();
                $("#form_code").val("");
                $("#form_title").val("");
                $("#form_value").val("");

                $("#add_message").addClass("displaynone");
                $('#myModal').modal('show');

            });
        });
    </script>

@endsection