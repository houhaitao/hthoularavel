@extends('admin.main')
@section('title','菜单管理')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li>
                <a href="#">Dashboard</a>
            </li>
        </ul>
    </div>


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
                    <form role="form" >
                        <div class="col-md-12">
                            <div class="form-group col-md-4">
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                            </div>
                            <div class="form-group  col-md-4">
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
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
                    <h2><i class="glyphicon glyphicon-user"></i> 菜单管理</h2>

                    <div class="box-icon">

                        <a href="#" class="btn btn-minimize btn-round btn-default"><i
                                    class="glyphicon glyphicon-chevron-up"></i></a>

                    </div>
                </div>
                <div class="box-content">
                    <div class="alert alert-info displaynone"></div>
                    <form action="">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox"/></th>
                                <th>排序</th>
                                <th>菜单名称</th>
                                <th>菜单地址</th>
                                <th>菜单类型</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $r)
                            <tr>
                                <td><input type="checkbox" name="id[]" value="{{$r->id}}}"/></td>
                                <td><input size="4" value="{{$r->listorder}}" type="text"/></td>
                                <td>{{$r->myname}}</td>
                                <td class="center">@if(!empty($r->url)){{$r->url}}@else空@endif</td>
                                <td class="center">@if($r->isfold=='0')链接@else目录@endif</td>
                                <td class="center">
                                    <a class="btn btn-info" href="#">
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
                                <input type="submit" class="btn btn-danger" value="删除"/>&nbsp;
                                <input type="submit" class="btn btn-default" value="排序"/>&nbsp;
                                <input type="button" id="op_add_form" class="btn btn-success" value="添加"/>
                            </div>
                            <div class="col-md-8 no-padding no-margin myright">
                                <ul class="pagination pagination-centered">
                                    <li><a href="#">Prev</a></li>
                                    <li class="active">
                                        <a href="#">1</a>
                                    </li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">Next</a></li>
                                </ul>
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
                    <form action="{{Config::get('hthou.local_path')}}admin/menu" name="add_form" method="post"
                          onsubmit="return r_submit(this)">
                        <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h3>添加菜单</h3>
                    </div>
                    <div class="modal-body">
                        <div id="add_menu" class="alert alert-info displaynone"></div>
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" name="myname" placeholder="菜单名称">
                        </div>
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" name="url" placeholder="菜单地址">
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


        <!-- content ends -->
    </div><!--/#content.col-md-0-->


@endsection