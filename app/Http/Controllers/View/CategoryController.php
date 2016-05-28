<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\PdtContent;
use App\Entity\PdtImages;
use Log;

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

     public function toPdtContent($product_id){
        $product = Product::find($product_id);
        $pdtContent=PdtContent::where('product_id',$product_id)->first();
        $pdt_images = PdtImages::where('product_id', $product_id)->get();
        return View("pdt_content")->with('product',$product)->with('pdtContent',$pdtContent)->with('pdt_images',$pdt_images);
        //return View("pdt_content")->with('product',$product)->with('pdtContent',$pdtContent);
    }

}
