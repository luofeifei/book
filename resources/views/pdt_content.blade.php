@extends('master')

{{-- 不加双花括号 --}}
@section('title',"{$product->name}")

@section('content')
  {{-- 内容 轮播图 富文本 --}}
  <link rel="stylesheet" type="text/css" href="/css/swipe.css">
    <div class="addWrap">
    <div class="swipe" id="mySwipe">
      <div class="swipe-wrap">
        @foreach($pdt_images as $pdt_image)
        <div>
          <a href="javascript:;"><img class="img-responsive" src="{{$pdt_image->image_path}}" /></a>
        </div>
        @endforeach
      </div>
    </div>
    <ul id="position">
      @foreach($pdt_images as $index => $pdt_image)
        <li class={{$index == 0 ? 'cur' : ''}}></li>
      @endforeach
    </ul>
  </div>
 
  <div class="weui_cells_title">
    <span class="bk_title">{{$product->name}}</span>
    <span class="bk_price" style="float:right;color:red" >¥ {{$product->price}}</span>
  </div>
  <div class="weui_cells">
     <div class="weui_cell">
      <p class="bk_summary">{{$product->summary}}</p>
     </div>
  </div>

  {{-- 内容 轮播图 富文本 --}}
  <div class="weui_cells_title">
  </div>
  <div class="weui_cells">
     <div class="weui_cell">
      <p>
        {{$pdtContent->content}}
      </p>
     </div>
  </div>
  {{-- 加入购物车 结算--}}
  <div class="bk_fix_bottom">
    <div class="bk_half_area">
        <button  class="weui_btn weui_btn_primary" onclick="_addCart();">加入购物车</a>
    </div>

    <div class="bk_half_area">
        <button  class="weui_btn weui_btn_default" >结算<span id="cart_num" class="m3_price">{{$count}}</span></a>
    </div>
  </div>

@endsection

@section('my-js')
  <script type="text/javascript" src="/js/swipe.min.js"></script>
  <script type="text/javascript">

  var bullets = document.getElementById('position').getElementsByTagName('li');
  Swipe(document.getElementById('mySwipe'), {
    auto: 5000,
    continuous: true,
    disableScroll: false,
    callback: function(pos) {
      var i = bullets.length;
      while (i--) {
        bullets[i].className = '';
      }
      bullets[pos].className = 'cur';
    }
  });

  function _addCart(){
     //localStorage
     //sessionStorage
     //laravel cookie   (product_id:cart_num)(1:5,2:4) ajax 方式添加购物车商品的数量 
     var product_id="{{$pdtContent->id}}";
      $.ajax({
              type: "GET",
              url: '/service/cart/add/'+product_id,
              dataType: 'json',
              cache: false,
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

                //成功
                var num=$('#cart_num').html();
                if (num=='') num=0;
                $('#cart_num').html(Number(num)+1);

              },
              error: function(xhr, status, error) {
                // console.log(xhr);
                console.log(status);
                //console.log(error);
              }});

    }
  </script>
@endsection