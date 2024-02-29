<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MultiImage;
use App\Models\SubCategory;
use App\Models\Product;

class ProductController extends Controller
{
    public function AllProduct(){
        $products = Product::latest()->get();
        return view('backend.product.product_all', compact('products'));
    } // end method

    public function AddProduct(){
       $brands = Brand::latest()->get();
       $categories = Category::latest()->get();
       $subcategory = SubCategory::latest()->get();
        return view('backend.product.product_add', compact('brands', 'categories', 'subcategory'));
    } // end method
}
