<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;

use App\Models\M3Result;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addCart(Request $req,$product_id){
    	//从cookie中取出购物车商品数量信息－》显示到页面 (1:5,2:3)

    	$bk_cart=$req->cookie('bk_cart');
    	
        $bk_arr=$bk_cart!=null?explode(',', $bk_cart):array();
        //查找为product_id的
        $count=1;
        if ($bk_arr) {
        	foreach ($bk_arr as &$value) {
        		$index=strpos($value, ':');                
        		if (substr($value,0,$index)==$product_id) {
        			# 找到原有的基础上＋1    			
                    $count=((int)substr($value, $index+1))+1;
                    $value=$product_id.":".$count;
        			break;
        		}
        	}
        }
        # 未找到
        if ($count==1) {
        	array_push($bk_arr, $product_id.":".$count);
        }
        
        
        $m3_result=new M3Result;
    	$m3_result->status=0;
        $m3_result->message="添加成功";
        #重新写入cookie
        return response($m3_result->toJson())->withCookie('bk_cart',implode(',', $bk_arr));

    }
}
