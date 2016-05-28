<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Entity\Category; 
use App\Models\M3Result;

class CategoryController extends Controller
{
    public function toCategory($parent_id){
        $categorys = Category::where('parent_id',$parent_id)->get();
        //json返回 laravel自动以json格式返回return $categorys;
        $m3Result=new M3Result;
        $m3Result->status=0;
        $m3Result->message="返回成功";
        $m3Result->categorys=$categorys;

        return $m3Result->toJson();
    }

    

}
