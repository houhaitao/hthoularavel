/**
 * Created by hthou on 2015/10/27.
 */
// checkbox全选、全取消
function checkall(obj,chname)
{
    $("input[type=checkbox]:enabled[name='"+chname+"[]']").prop('checked',$(obj).prop('checked'));
}

//改变form地址
function change_opt(formname,actionurl)
{
    //document.getElementById(formname).action = actionurl;
    //submit(document.getElementById(formname))
    document.forms[formname].action = actionurl;
    r_submit(document.forms[formname]);
}
function r_submit(hht_obj)
{
    var now = Date.parse(new Date());
    if($(hht_obj).data("lastsubmit")){
        if(now - $(hht_obj).data("lastsubmit") < 2000) return false; //同一个表单,2秒钟只能提交1次
    }
    $(hht_obj).data("lastsubmit", now);

    var formaction = hht_obj.getAttribute('action');
    var elements = document.getElementsByTagName('span');
    var form_id='';
    var length = elements.length;
    var idlist = new Array();
    for(i=0;i<length;i++)
    {
        form_id = elements[i].id;
        if(form_id.substring(0,9)=='response_')
        {
            $('#'+elements[i].id).attr('class','');
            $('#'+elements[i].id).text('');
        }
    }

    make_iframe();

    hht_obj.setAttribute('target','r_post');
    var domain = 'http://' + window.location.host;

    if(formaction=='' || formaction==domain ||  formaction==domain+'/')
    {
        var newaction= make_url(self.location);
    }
    else
    {

        var newaction= make_url(formaction);
    }
    hht_obj.setAttribute('action',newaction);
    hht_obj.setAttribute('method',"post");
    hht_obj.submit();

    return false;
}

function r_call(str)
{
    eval(str);
}

function make_iframe()
{
    try{
        var a = document.getElementById('r_post').style.display;
    }
    catch(ex){
        if (!window.addEventListener) {
            frm = document.createElement('<iframe id=\'r_post\' name=\'r_post\'>');
        } else {
            var frm = document.createElement("iframe");
            frm.id="r_post";
            frm.name="r_post";
        }
        document.body.appendChild(frm);
        document.getElementById('r_post').style.display="none";
    }
}

function r_request(r_url)
{
    make_iframe();
    document.getElementById("r_post").src=make_url(r_url);
    return false;
}

function make_url(r_url)
{
    var my_url=r_url+'';
    if(my_url.indexOf('edufepm=iframe')==-1)
    {
        if(my_url.indexOf('?')>0)
        {
            return my_url+'&edufepm=iframe';
        }
        else
        {
            return my_url+'?edufepm=iframe';
        }
    }
    return my_url;
}

function my_request(r_url,msg)
{
    if(msg!=undefined && msg!='' && confirm(msg)==false)
    {
        ;
    }
    else
    {
        r_request(r_url);
    }
}

function self_ref()
{

    self.location = self.location;
}

function my_alert(id,type,text)
{
    $("#"+id).removeClass();
    $("#"+id).addClass("alert alert-"+type);
    $("#"+id).html(text);
}

function clear_alert()
{
    $(".alert").addClass("displaynone");
}