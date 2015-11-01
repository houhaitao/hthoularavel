/**
 * Created by hthou on 2015/10/27.
 */
$(document).ready(function () {
    menu_url = '/admin/menu/';
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
        $('#myModal').modal('show');

    })
    $(".menu_mod").click(function(e){
        var id_str = $(this).attr('id');
        var id = id_str.replace('mod_','');
        var url = menu_url+id;
        $.get(url, function(result){
            var myobj = JSON.parse(result);
            $("#form_myname").val(myobj.myname);
            $("#form_url").val(myobj.url);
            if($("#form_id").val() == undefined)
            {
                $("#form_url").after("<input type=\"hidden\" name=\"id\" id=\"form_id\" value=\""+myobj.id+"\">");
            }
            else
            {
                $("#form_id").val(id);
            }

            $('#myModal').modal('show');
        });
    });


});