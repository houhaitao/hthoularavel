/**
 * Created by hthou on 2015/10/27.
 */
function make_sub_menu(data)
{
    var html = "";
    var size = data.length;
    var index=0;
    var tmp_obj
    var menu_img;
    for(index=0;index<size;index++)
    {
        tmp_obj = data[index];
        menu_img = (tmp_obj.info.image=='' || tmp_obj.info.image==null) ? 'home' : tmp_obj.info.image;
        if(tmp_obj.c=='1')
        {
            html += "<li><a class=\"ajax-link\" href=\""+tmp_obj.info.url+"\"><i class=\"glyphicon";
            html += " glyphicon-"+menu_img+"\"></i><span>"+tmp_obj.info.myname+"</span></a></li>";
        }
        else
        {
            html += "<li class=\"accordion\">";
            html += "<a href=\"#\" vardata=\""+tmp_obj.info.id+"\"><i class=\"glyphicon glyphicon-"+menu_img+"\"></i><span>"+tmp_obj.info.myname+"</span></a>";
            html += "<ul class=\"nav nav-pills nav-stacked\">";
            html += make_sub_menu(tmp_obj.c);
            html+= "</ul></li>";
        }
    }
    return html;
}
var menu_url = '/admin/menu/';
var manager_url = '/admin/manager/';
var resource_url = '/admin/resource/';
$(document).ready(function () {

    //全局
    $("#checkall").click(function(){
        var myclass = $(this).attr("children");
        if($(this).attr('checked') == undefined )
        {
            $("."+myclass).each(function(){
                $(this).prop("checked",true);
            });
            $(this).attr('checked','true');
        }
        else
        {
            $("."+myclass).each(function(){
                $(this).prop("checked",false);
            });
            $(this).removeAttr('checked');
        }

    });

});