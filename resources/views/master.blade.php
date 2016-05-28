<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>@yield('title')</title>
	<link rel="stylesheet" type="text/css" href="/css/weui.css">
	<link rel="stylesheet" type="text/css" href="/css/book.css">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">

</head>
<body>
<div class="bk_title_bar">
    <img class="bk_back" src="/images/back.png" onclick="history.go(-1);">
    <p class="bk_title_content"></p>
    <img class="bk_menu" src="/images/menu.png" onclick="onMenuClick();" >
</div>
  <div class="page">
  	   @yield('content')
  </div>	
        <!-- tooltips -->
        <div class="bk_toptips"><span></span></div>
        
        {{-- <div id="global_menu" onclick="onMenuClick();">
             <div></div>
        </div> --}}


        <div id="actionSheet_wrap">
        	<div class="weui_mask_transition" id="mask"></div>
        	<div class="weui_actionsheet" id="weui_actionsheet">
        	  <div class="weui_actionsheet_menu">
                    <div class="weui_actionsheet_cell">示例菜单</div>
                    <div class="weui_actionsheet_cell">示例菜单</div>
                    <div class="weui_actionsheet_cell">示例菜单</div>
                    <div class="weui_actionsheet_cell">示例菜单</div>
              </div> 
            </div>       
        </div>
        <!--BEGIN actionSheet-->

            

</body>

<script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  //标题栏
  $(".bk_title_content").html(document.title);
  function hideActionSheet(weuiActionsheet, mask) {
    weuiActionsheet.removeClass('weui_actionsheet_toggle');
    mask.removeClass('weui_fade_toggle');
    weuiActionsheet.on('transitionend', function () {
        mask.hide();
    }).on('webkitTransitionEnd', function () {
        mask.hide();
    })
}

function onMenuClick () {
    var mask = $('#mask');
    var weuiActionsheet = $('#weui_actionsheet');
    weuiActionsheet.addClass('weui_actionsheet_toggle');
    mask.show().addClass('weui_fade_toggle').click(function () {
        hideActionSheet(weuiActionsheet, mask);
    });
    $('#actionsheet_cancel').click(function () {
        hideActionSheet(weuiActionsheet, mask);
    });
    weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');
}

</script>
   @yield('my-js')
</html>