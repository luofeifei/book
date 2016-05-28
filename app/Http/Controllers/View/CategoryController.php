<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\PdtContent;
use App\Entity\PdtImages;
use Log;
use Illuminate\Http\Request;
class CategoryController extends Controller
{
    public function toCategory(){
        Log::error("进入书籍类别");
        $categorys = Category::whereNull('parent_id')->get();
        return View("category")->with('categorys',$categorys);
    }

    public function toProduct($category_id){
        $products = Product::where('category_id',$category_id)->get();

        return View("product")->with('products',$products);
    }

     public function toPdtContent(Request $req,$product_id){
        $product = Product::find($product_id);
        $pdtContent=PdtContent::where('product_id',$product_id)->first();
        $pdt_images = PdtImages::where('product_id', $product_id)->get();

        $bk_cart=$req->cookie('bk_cart');
        
        $bk_arr=$bk_cart!=null?explode(',', $bk_cart):array();
        //查找为product_id的
        $count=0;
        if ($bk_arr) {
            foreach ($bk_arr as $value) {
                $index=strpos($value, ':');                
                if (substr($value,0,$index)==$product_id) {
                    # 找到原有的基础上＋1                
                    $count=((int)substr($value, $index+1));
                    break;
                }
            }
        }
        return View("pdt_content")->with('product',$product)
                                  ->with('pdtContent',$pdtContent)
                                  ->with('pdt_images',$pdt_images)
                                  ->with('count',$count);
        //return View("pdt_content")->with('product',$product)->with('pdtContent',$pdtContent);
    }

}
