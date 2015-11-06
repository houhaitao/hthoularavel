<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        ===
        This comment should NOT be removed.

        Charisma v2.0.0

        Copyright 2012-2014 Muhammad Usman
        Licensed under the Apache License v2.0
        http://www.apache.org/licenses/LICENSE-2.0

        http://usman.it
        http://twitter.com/halalit_usman
        ===
    -->
    <meta charset="utf-8">
    <base href="/" />
    <title>我的测试-@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link id="bs-css" href="/css/bootstrap-cerulean.min.css" rel="stylesheet">

    <link href="/css/charisma-app.css" rel="stylesheet">
    <link href='/bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='/bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='/bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='/bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='/bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href='/css/jquery.noty.css' rel='stylesheet'>
    <link href='/css/noty_theme_default.css' rel='stylesheet'>
    <link href='/css/elfinder.min.css' rel='stylesheet'>
    <link href='/css/elfinder.theme.css' rel='stylesheet'>
    <link href='/css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='/css/uploadify.css' rel='stylesheet'>
    <link href='/css/animate.min.css' rel='stylesheet'>
    <link href='/css/main.css' rel='stylesheet'>

    <!-- jQuery -->
    <script src="/bower_components/jquery/jquery.min.js"></script>
    <script src="/js/common.js"></script>
    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->


    <!-- The fav icon -->
    <link rel="shortcut icon" href="/img/favicon.ico">

</head>

<body>
<!-- topbar starts -->
<div class="navbar navbar-default" role="navigation">

    <div class="navbar-inner">
        <button type="button" class="navbar-toggle pull-left animated flip">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html"> <img alt="Charisma Logo" src="img/logo20.png" class="hidden-xs"/>
            <span>Charisma</span></a>

        <!-- user dropdown starts -->
        <div class="btn-group pull-right">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> admin</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Profile</a></li>
                <li class="divider"></li>
                <li><a href="login.html">Logout</a></li>
            </ul>
        </div>
        <!-- user dropdown ends -->

        <!-- theme selector starts -->
        <div class="btn-group pull-right theme-container animated tada">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-tint"></i><span
                        class="hidden-sm hidden-xs"> Change Theme / Skin</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" id="themes">
                <li><a data-value="classic" href="#"><i class="whitespace"></i> Classic</a></li>
                <li><a data-value="cerulean" href="#"><i class="whitespace"></i> Cerulean</a></li>
                <li><a data-value="cyborg" href="#"><i class="whitespace"></i> Cyborg</a></li>
                <li><a data-value="simplex" href="#"><i class="whitespace"></i> Simplex</a></li>
                <li><a data-value="darkly" href="#"><i class="whitespace"></i> Darkly</a></li>
                <li><a data-value="lumen" href="#"><i class="whitespace"></i> Lumen</a></li>
                <li><a data-value="slate" href="#"><i class="whitespace"></i> Slate</a></li>
                <li><a data-value="spacelab" href="#"><i class="whitespace"></i> Spacelab</a></li>
                <li><a data-value="united" href="#"><i class="whitespace"></i> United</a></li>
            </ul>
        </div>
        <!-- theme selector ends -->

        <ul class="collapse navbar-collapse nav navbar-nav top-menu">
            <li><a href="#"><i class="glyphicon glyphicon-globe"></i> Visit Site</a></li>
            <li class="dropdown">
                <a href="#" data-toggle="dropdown"><i class="glyphicon glyphicon-star"></i> Dropdown <span
                            class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                    <li class="divider"></li>
                    <li><a href="#">One more separated link</a></li>
                </ul>
            </li>
            <li>
                <form class="navbar-search pull-left">
                    <input placeholder="Search" class="search-query form-control col-md-10" name="query"
                           type="text">
                </form>
            </li>
        </ul>

    </div>
</div>
<!-- topbar ends -->


<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu admin-left-menu">

                    </ul>

                </div>
            </div>
        </div>
        <!--/span-->
        <!-- left menu ends -->





        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            @yield('content')
        </div>
    </div><!--/fluid-row-->






    <footer class="row">
        <p class="col-md-9 col-sm-9 col-xs-12 copyright">&copy; <a href="http://usman.it" target="_blank">Muhammad
                Usman</a> 2012 - 2015</p>

        <p class="col-md-3 col-sm-3 col-xs-12 powered-by">Powered by: <a
                    href="http://usman.it/free-responsive-admin-template">Charisma</a></p>
    </footer>

</div><!--/.fluid-container-->

<!-- external javascript -->

<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- library for cookie management -->
<script src="/js/jquery.cookie.js"></script>
<!-- calender plugin -->
<script src='/bower_components/moment/min/moment.min.js'></script>
<script src='/bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='/js/jquery.dataTables.min.js'></script>

<!-- select or dropdown enhancer -->
<script src="/bower_components/chosen/chosen.jquery.min.js"></script>
<!-- plugin for gallery image view -->
<script src="/bower_components/colorbox/jquery.colorbox-min.js"></script>
<!-- notification plugin -->
<script src="/js/jquery.noty.js"></script>
<!-- library for making tables responsive -->
<script src="/bower_components/responsive-tables/responsive-tables.js"></script>
<!-- tour plugin -->
<script src="/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
<!-- star rating plugin -->
<script src="/js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="/js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="/js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="/js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="/js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="/js/hthou.js"></script>

<script src="/js/charisma.js"></script>
<script>
    $.get(menu_url+"ajaxMenuTree",function(data){
        var menu_data = JSON.parse(data);
        var html = "<li class=\"nav-header\">主菜单</li>";
        var size = menu_data.length;
        var index=0;
        var tmp1_obj;
        var menu_img;
        var current_menu = $.cookie("currentMenu");
        var menu_tree;

        for(index=0;index<size;index++)
        {
            tmp1_obj = menu_data[index];
            menu_img = (tmp1_obj.info.image=='' || tmp1_obj.info.image==null) ? 'home' : tmp1_obj.info.image;
            if(tmp1_obj.c=='1')
            {
                html += "<li><a class=\"ajax-link\" href=\""+tmp1_obj.info.url+"\"><i class=\"glyphicon";
                html += " glyphicon-"+menu_img+"\"></i><span>"+tmp1_obj.info.myname+"</span></a></li>";
            }
            else
            {
                html += "<li class=\"accordion\">";
                html += "<a href=\"#\" vardata=\""+tmp1_obj.info.id+"\"><i class=\"glyphicon glyphicon-"+menu_img+"\"></i><span>"+tmp1_obj.info.myname+"</span></a>";
                html += "<ul class=\"nav nav-pills nav-stacked\">";
                html += make_sub_menu(tmp1_obj.c);
                html+= "</ul></li>";
            }
        }
        $(".admin-left-menu").html(html);
        if(current_menu != undefined || current_menu != null || current_menu !=='')
        {
            $.get(menu_url+"ajaxMenuPath/"+current_menu,function(path_data){
                menu_tree = JSON.parse(path_data);
                size = menu_tree.length;
                for(index=0;index<size;index++)
                {
                    $("[vardata='"+menu_tree[index]+"']").parent().addClass('active');
                    $("[vardata='"+menu_tree[index]+"']").siblings('ul').slideToggle();;
                }
                $('.accordion li.active:first').parents('ul').slideDown();
            });
        }

        $('.accordion > a').click(function (e) {
            e.preventDefault();
            var $ul = $(this).siblings('ul');
            var $li = $(this).parent();
            if ($ul.is(':visible'))
                $li.removeClass('active');
            else
            {
                $li.siblings('li').removeClass('active');
                $li.siblings('li').children('ul').slideUp();
                $li.addClass('active');
                var value = $(this).attr("vardata");
                $.cookie('currentMenu',value);
            }
            $ul.slideToggle();
        });

        //$('.accordion li.active:first').parents('ul').slideDown();


        //other things to do on document ready, separated for ajax calls
        docReady();
    });
</script>

</body>
</html>