<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MultiImage;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\User;

class IndexController extends Controller

{
    public function Index(){
        $category = Category::skip(0)->first();
        $products = Product::where('status',1)->where('category_id', $category->id)->orderBy('id', 'DESC')->limit(5)->get();

        $Sweethome = Category::skip(2)->first();
        $Sweethome_1 = Product::where('status',1)->where('category_id', $category->id)->orderBy('id', 'DESC')->limit(5)->get();

        return view('frontend.index', compact('category', 'products', 'Sweethome', 'Sweethome_1'));

    } // End Method

    public function ProductDetails($id,$slug){

        $product = Product::findOrFail($id);

        $color = $product->product_color;
        $product_color = explode(',', $color);

        $size = $product->product_size;
        $product_size = explode(',', $size);

        $multiimage = MultiImage::where('product_id', $id)->get();

        $cat_id = $product->category_id;

        $relatedProduct = Product::where('category_id', $cat_id)->where('id', '!=', $id)->orderBy('id','DESC')->limit(4)->get();

        return view('frontend.product.product_details', compact('product', 'product_color', 'product_size', 'multiimage', 'relatedProduct'));

    } // End Product Details
}
