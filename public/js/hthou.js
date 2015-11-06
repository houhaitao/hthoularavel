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

    //菜单管理js
    $("#op_add_form").click(function(e){
        e.preventDefault();
        $("#form_myname").val("");
        $("#form_url").val("");
        if($("#form_id").val() != undefined)
        {
            $("#form_id").remove();
        }
        $("#add_menu").addClass("displaynone");
        $('#myModal').modal('show');

    });
    $(".menu_mod").click(function(e){
        var id_str = $(this).attr('id');
        var id = id_str.replace('mod_','');
        var url = menu_url+id;
        $.get(url, function(result){
            var myobj = JSON.parse(result);
            $("#form_myname").val(myobj.myname);
            $("#form_url").val(myobj.url);
            $("#form_image").val(myobj.image);
            if($("#form_id").val() == undefined)
            {
                $("#form_url").after("<input type=\"hidden\" name=\"id\" id=\"form_id\" value=\""+myobj.id+"\">");
            }
            else
            {
                $("#form_id").val(id);
            }

            $('#myModal').modal('show');
            $("#add_menu").addClass("displaynone");
        });
    });


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
        $('#myModal').modal('show');

    });

    $(".manager_mod").click(function(e){
        var id_str = $(this).attr('id');
        var id = id_str.replace('mod_','');
        var url = manager_url+id;
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
            $("#add_manager").addClass("displaynone");
            $('#myModal').modal('show');

        });
    });



});