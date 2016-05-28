@extends('master')
@include('component.loading')

@section('title',"登录")

@section('content')
  <div class="weui_cells_title">表单</div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd">
                <label class="weui_label">账号</label>
                </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" pattern="[0-9]*" placeholder="邮箱或手机号" name="username" value='13246938330' />
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd">
                <label class="weui_label">密码</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="number" pattern="[0-9]*" placeholder="不少于6位" name="password" value='123456'/>
            </div>
        </div>
        <div class="weui_cell weui_vcode">
            <div class="weui_cell_hd">
                <label class="weui_label">验证码</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" placeholder="请输入验证码" name="validate_code"/>
            </div>
            <div class="weui_cell_ft">
                <img src="/service/validate_code/create/login" class="bk_validate_code" />
            </div>
        </div>
  </div>
  <div class="weui_cells_tips">底部说明文字底部说明文字</div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_primary" href="javascript:" onclick="onLoginClick();">登录</a>
        </div>
  <a href="/register" class="bk_bottom_tips bk_important">没有账号？去注册</a>
@endsection

@section('my-js')
 <script type="text/javascript">
   function onLoginClick(){
             var username=$('input[name=username]').val();
             var password=$('input[name=password]').val();
             var validate_code=$('input[name=validate_code]').val();
              //前端验证
              // if (verifyEmail(email,password,validate_code)==false) {
              //  return;
              // };
            
            var data={username: username, password: password,validate_code: validate_code, _token: "{{csrf_token()}}"};

            $.ajax({
              type: "POST",
              url: '/service/login',
              dataType: 'json',
              cache: false,
              data: data,
              success: function(data) {
                if(data == null) {
                  $('.bk_toptips').show();
                  $('.bk_toptips span').html('服务端错误');
                  setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                  return;
                }
                if(data.status != 0) {
                  $('.bk_toptips').show();
                  $('.bk_toptips span').html(data.message);
                  setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                  return;
                }

                $('.bk_toptips').show();
                $('.bk_toptips span').html(data.message);
                setTimeout(function() {$('.bk_toptips').hide();}, 2000);

                //注册成功 自动登录

              },
              error: function(xhr, status, error) {
                // console.log(xhr);
                console.log(status);
                //console.log(error);
              }});
        
  }

 $(".bk_validate_code").click(function() {
 	$src="/service/validate_code/create/login?"+Math.random();
 	$(this).attr("src",$src);
 })
 </script>
@endsection