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
        $category = Category::where('category_name', 'Fashion')->first();
        $products = Product::where('status',1)->where('category_id', $category->id)->orderBy('id', 'DESC')->limit(10)->get();

        $Sweethome = Category::where('category_name', 'Sweet Home')->first();
        $Sweethome_category = Product::where('status',1)->where('category_id',
         $Sweethome->id)->orderBy('id', 'DESC')->limit(5)->get();

        $mobile = Category::where('category_name', 'Mobile')->first();
        $mobile_category = Product::where('status',1)->where('category_id',
         $mobile->id)->orderBy('id', 'DESC')->limit(5)->get();

        $hot_deals = Product::where('hot_deals', 1)->where('discount_price',
        '!=', NULL)->orderBy('id', 'DESC')->limit(4)->get();

        $special_offer = Product::where('special_offer', 1)->orderBy('id', 'DESC')->limit(4)->get();

        $new = Product::where('status',1)->orderBy('id', 'DESC')->limit(4)->get();

        $special_deals = Product::where('special_deals',1)->orderBy('id', 'DESC')->limit(4)->get();

        return view('frontend.index', compact('category','products', 'Sweethome', 'Sweethome_category',
          'mobile', 'mobile_category', 'hot_deals','special_offer',  'new','special_deals'));

    } // End Method

    public function ProductDetails($id,$slug){

        $product = Product::with(['category', 'subcategory'])->findOrFail($id);

        $color = $product->product_color;
        $product_color = explode(',', $color);

        $size = $product->product_size;
        $product_size = explode(',', $size);

        $multiimage = MultiImage::where('product_id', $id)->get();

        $cat_id = $product->category_id;

        $relatedProduct = Product::where('category_id', $cat_id)->where('id', '!=', $id)->orderBy('id','DESC')->limit(4)->get();

        return view('frontend.product.product_details', compact('product', 'product_color', 'product_size', 'multiimage', 'relatedProduct'));

    } // End Product Details

    public function VendorDetails($id){

        $vendor = User::findOrFail($id);
        $vProduct = Product::where('vendor_id', $id)->get();
        return view('frontend.vendor.vendor_details', compact('vendor', 'vProduct'));

    } // End Method

    public function VendorAll(){
        $vendors = User::where('status', 'active')->where
        ('role', 'vendor')->orderBy('id', 'DESC')->get();

        return view('frontend.vendor.vendor_all', compact('vendors'));
    } // End Method

    public function CatWiseProduct(Request $request,$id,$slug){
        $products = Product::where('status', 1)->where('category_id', $id)->orderBy('id', 'DESC')->get();
        $categories = Category::orderBy('category_name', 'ASC')->get();

        $vercat = Category::where('id', $id)->first();
        $newProduct = Product::orderBy('id', 'DESC')->limit(3)->get();

        return view('frontend.product.category_view', compact('products', 'categories', 'vercat', 'newProduct'));
    } // End Method

    public function SubCatWiseProduct(Request $request,$id,$slug){
        $products = Product::where('status', 1)->where('subCategory_id', $id)->orderBy('id', 'DESC')->get();
        $categories =Category::orderBy('category_name', 'ASC')->get();

        $subvercat = SubCategory::where('id',$id)->first();
        $newProduct = Product::orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.product.subcategory_view', compact('products', 'categories','subvercat', 'newProduct'));


    } // End Method

    public function ProductViewAjax($id){
        $product = Product::with('category', 'brand')->findOrFail($id);

        $color = $product->product_color;
        $product_color = explode(',', $color);

        $size = $product->product_size;
        $product_size = explode(',', $size);

        return response()->json(array(

            'product' => $product,
            'color' => $product_color,
            'size' => $product_size,

        ));

    } // End Method

}
