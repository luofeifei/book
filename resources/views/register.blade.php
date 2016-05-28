
@extends('master')
@section("title","注册")

@section('content')
    <div class="weui_cells_title">注册方式</div>
    <div class="weui_cells weui_cells_radio">
      <label class="weui_cell weui_check_label" for="x11">
        <div class="weui_cell_bd weui_cell_primary">
            <p>手机号注册</p>
        </div>
        <div class="weui_cell_ft">
            <input type="radio" class="weui_check" name="register_type" id="x11" checked="checked">
            <span class="weui_icon_checked"></span>
        </div>
      </label>
      <label class="weui_cell weui_check_label" for="x12">
        <div class="weui_cell_bd weui_cell_primary">
            <p>邮箱注册</p>
        </div>
        <div class="weui_cell_ft">
            <input type="radio" name="register_type" class="weui_check" id="x12">
            <span class="weui_icon_checked"></span>
        </div>
      </label></div>
    {{-- <div class="weui_cells_title">表单1</div> --}}
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd">
                <label class="weui_label">手机号</label>
                </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" pattern="[0-9]*" value="13246938330" placeholder="邮箱或手机号" name="phone" />
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd">
                <label class="weui_label">密码</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="password" value="123456" pattern="[0-9]*" placeholder="不少于6位" name="password" />
            </div>
        </div>
        <div class="weui_cell weui_vcode">
            <div class="weui_cell_hd">
                <label class="weui_label">验证码</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input"  placeholder="请输入验证码" name="validate_code"/>
            </div>
            <div class="weui_cell_ft">
                <img src="/service/validate_code/create/login" class="bk_validate_code" />
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd">
                <label class="weui_label">手机验证码</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="number" placeholder="" value="786437" name="phone_code" />                
            </div>
            <p class="bk_important bk_phone_code_send">发送验证码</p>
              <div class="weui_cell_ft">
            </div> 

        </div></div>
    <div class="weui_cells weui_cells_form" style="display:none">
        <div class="weui_cell">
            <div class="weui_cell_hd">
                <label class="weui_label">邮箱</label>
                </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" pattern="[0-9]*" value="anrenluofeifei@163.com" placeholder="邮箱或手机号" name="email" />
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd">
                <label class="weui_label">密码</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="password" value="123456" pattern="[0-9]*" placeholder="不少于6位" name="emailpassword" />
            </div>
        </div>
        <div class="weui_cell weui_vcode">
            <div class="weui_cell_hd">
                <label class="weui_label">验证码</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input"  placeholder="请输入验证码" name="emailvalidate_code" />
            </div>
            <div class="weui_cell_ft">
                <img src="/service/validate_code/create/login" class="bk_validate_code" />
            </div>
        </div></div>
    <div class="weui_cells_tips">底部说明文字底部说明文字</div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_primary" href="javascript:" onclick="onRegisterClick();">注册</a>
        </div>
    <a href="/login" class="bk_bottom_tips bk_important">已有账号？请登录</a>
@endsection

@section('my-js')
<script type="text/javascript">
  // $("#x12").next().hide();
  function onRegisterClick(){
      $('input:radio[name="register_type"]').each(function(index,el) {
        if ($(this).attr("checked")=="checked") {
            var email="";
            var phone="";
            var password="";
            //var confirm="";
            var phone_code="";
            var validate_code="";

            var id=$(this).attr("id");
            if (id=="x11") {//手机注册方式
              phone=$('input[name=phone]').val();
              password=$('input[name=password]').val();
              phone_code=$('input[name=phone_code]').val();
              validate_code=$('input[name=validate_code]').val();

              //前端验证
              if (verifyPhone(phone,password,validate_code,phone_code)==false) {
                return;
              };}
            else if(id=="x12"){
              email=$('input[name=email]').val();
              password=$('input[name=emailpassword]').val();
              validate_code=$('input[name=emailvalidate_code]').val();
              //前端验证
              if (verifyEmail(email,password,validate_code)==false) {
               return;
              };
            }
            
            var data={phone: phone, email: email, password: password,
                 phone_code: phone_code, validate_code: validate_code, _token: "{{csrf_token()}}"};
            console.log(data);
            $.ajax({
              type: "POST",
              url: '/service/register',
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
                $('.bk_toptips span').html('注册成功');
                setTimeout(function() {$('.bk_toptips').hide();}, 2000);

                //注册成功 自动登录

              },
              error: function(xhr, status, error) {
                // console.log(xhr);
                console.log(status);
                //console.log(error);
              }});
        };
       
    })
  }

  function verifyPhone(phone,password,validate_code,phone_code){
    // 手机号不为空
    if(phone == '') {
      $('.bk_toptips').show();
      $('.bk_toptips span').html('请输入手机号');
      setTimeout(function() {$('.bk_toptips').hide();}, 2000);
      return false;
      }
      // 手机号格式
      if(phone.length != 11 || phone[0] != '1') {
      $('.bk_toptips').show();
      $('.bk_toptips span').html('手机格式不正确');
      setTimeout(function() {$('.bk_toptips').hide();}, 2000);
      return false;
      }
      if(password == '') {
      $('.bk_toptips').show();
      $('.bk_toptips span').html('密码不能为空');
      setTimeout(function() {$('.bk_toptips').hide();}, 2000);
      return false;
      }
      if(password.length < 6) {
        $('.bk_toptips').show();
        $('.bk_toptips span').html('密码不能少于6位');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return false;
      }
      // if(password) {
      //   $('.bk_toptips').show();
      //   $('.bk_toptips span').html('两次密码不相同!');
      //   setTimeout(function() {$('.bk_toptips').hide();}, 2000);
      //   return false;
      // }
      if(phone_code == '') {
        $('.bk_toptips').show();
        $('.bk_toptips span').html('手机验证码不能为空!');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return false;
      }
      if(phone_code.length != 6) {
        $('.bk_toptips').show();
        $('.bk_toptips span').html('手机验证码为6位!');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return false;
      }
      return true;
  }

  function verifyEmail(email,password,validate_code){
      
      // 邮箱不为空
     if(email == '') {
      $('.bk_toptips').show();
      $('.bk_toptips span').html('请输入邮箱');
      setTimeout(function() {$('.bk_toptips').hide();}, 2000);
      return false;
      }
     // 邮箱格式
     if(email.indexOf('@') == -1 || email.indexOf('.') == -1) {
      $('.bk_toptips').show();
      $('.bk_toptips span').html('邮箱格式不正确');
      setTimeout(function() {$('.bk_toptips').hide();}, 2000);
      return false;
     }
      if(password == '') {
        $('.bk_toptips').show();
        $('.bk_toptips span').html('密码不能为空');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return false;
      }
      if(password.length < 6) {
        $('.bk_toptips').show();
        $('.bk_toptips span').html('密码不能少于6位');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return false;
      }
      // if(password != confirm) {
      //   $('.bk_toptips').show();
      //   $('.bk_toptips span').html('两次密码不相同!');
      //   setTimeout(function() {$('.bk_toptips').hide();}, 2000);
      //   return false;
      // }
      if(validate_code == '') {
        $('.bk_toptips').show();
        $('.bk_toptips span').html('验证码不能为空!');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return false;
      }
      if(validate_code.length != 4) {
        $('.bk_toptips').show();
        $('.bk_toptips span').html('验证码为4位!');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return false;
      }
      return true;
  }

  $('input:radio[name="register_type"]').click(function(){
      //$('input:radio[name="register_type"]').attr
      var id=$(this).attr('id');
    if ($(this).attr('id')=="x11") {
        $("#x11").next().attr("checked",true);
        $("#x11").attr("checked",true);
        $("#x12").attr("checked",false);
        $(".weui_cells_form:eq(0)").show();
        $(".weui_cells_form:eq(1)").hide();
      };
      if ($(this).attr('id')=="x12") {
        $("#x12").next().attr("checked",true);
        $("#x12").attr("checked",true);
        $("#x11").attr("checked",false);
        $(".weui_cells_form:eq(0)").hide();
        $(".weui_cells_form:eq(1)").show();
      };
  })
  
  var enable=true; 
  //发送验证码
  $('.bk_phone_code_send').click(function(){
    if (enable==false) {
        return ;
      };
      enable=false;
      var num=60;
      //等1MIN 才能继续发
    var interval=window.setInterval(function(){
        $('.bk_phone_code_send').html(--num+"后重新发送");
        if (num==0) {
            enable=true;
            window.clearInterval(interval);
            $('.bk_phone_code_send').html("重新发送");
        }

      },1000);
    //请求控制器的方法 请求接口
    var phone=$('input[name="phone"]').val();
    if (phone=="") {
        return ;
    };
    //调用接口
    $.ajax({
        url:'/service/validate_code/create/send',
        //type:"POST",
        data:{phone:phone},
        dataType:"json",
        success:function(data){
            if (data==null) {
                //服务端错误 提示框；
                $(".bk_toptips").show();
                $(".bk_toptips span").html('服务端异常');
                setTimeout(function(){$(".bk_toptips").hide();},2000);
                return;
            };

            if (data.status!=0) {
                $(".bk_toptips").show();
                $(".bk_toptips span").html(data.message);
                setTimeout(function(){
                    $(".bk_toptips").hide();
                },2000);
                return;
            };

            $(".bk_toptips").show();
            $(".bk_toptips span").html(data.message);

            setTimeout(function(){
                    $(".bk_toptips").hide();
            },2000);
        },
        error:function(xhr,status,error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    })

  })
  
  //验证码
  $(".bk_validate_code").click(function() {
    $src="/service/validate_code/create/login?"+Math.random();
    $(this).attr("src",$src);
   })
</script>
@endsection