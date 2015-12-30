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
                                <input type="text" name="hostname" value="{{$name}}" class="form-control" id="exampleInputEmail1" placeholder="组名称" />

                                <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                            </div>
                            <div class="form-group col-md-4">
                                <select name="role" class="form-control">
                                    <option value="">所有角色</option>
                                    @foreach($roles as $k=>$v)
                                        <option value="{{$k}}" @if($k==$role) selected @endif>{{$v}}</option>
                                    @endforeach
                                </select>
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
                    <h2><i class="glyphicon glyphicon-user"></i> 节点管理</h2>

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
                                <th>hosts</th>
                                <th>ip</th>
                                <td>状态</td>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $r)
                                <tr>
                                    <td><input type="checkbox" name="id[]" class="ck" value="{{$r->id}}"/></td>
                                    <td>{{$r->hostname}}</td>
                                    <td>{{long2ip($r->ip)}}</td>
                                    <td>@if($r->status==1)初始化@elseif($r->status==2)正常运行@elseif($r->status==3)已停用@else故障@endif</td>
                                    <td class="center">
                                        @if($r->status==1)
                                        <a class="btn btn-info zc_mod" id="zc_{{$r->id}}" href="javascript:void(0);">
                                            <i class="glyphicon glyphicon-edit icon-white"></i>
                                            确认正常
                                        </a>
                                        @endif
                                        @if($r->status==2)
                                            <a class="btn btn-info priv_mod" href="javascript:void(0);">
                                                <i class="glyphicon glyphicon-edit icon-white"></i>
                                                节点下线
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div>
                            <div class="col-md-4">
                                <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                                <input type="button" onclick="change_opt('form1','{{$url}}/delete')" class="btn btn-danger" value="删除"/>&nbsp;

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
        });
    </script>

@endsection