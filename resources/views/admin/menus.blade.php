@extends('admin.main')
@section('title','菜单管理')
@section('content')
    <table width="100%" border="1">
        <tr><td>菜单名称</td><td>地址</td><td>类型</td><td>操作</td></tr>
        @foreach($data as $l)
        <tr><td>{{$l->myname}}</td>
            <td>{{$l->url}}</td>
            <td>@if($l->isfolder==0)
                    菜单
                @else
                    目录
                @endif
            </td>
            <td>修改</td>
        </tr>
        @endforeach
    </table>
@endsection