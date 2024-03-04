<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MultiImage;
use App\Models\SubCategory;
use App\Models\Product;
use Carbon\Carbon;

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

    public function StoreProduct(Request $request){
        $image = $request->file('product_thumbnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/products/thumbnail/'), $name_gen);
        $save_url = 'upload/products/thumbnail/'.$name_gen;

        $product_id = Product::insertGetID([

            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,

            'product_name' => $request->product_name,
            'product_slug' =>strtolower(str_replace(' ', '_', $request->product_name)),
            'product_code' => $request->product_code,

            'product_qty' => $request->product_qty,
            'product_tags' => $request->product_tags,
            'product_size' => $request->product_size,

            'product_color' => $request->product_color,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,

            'short_desc' => $request->short_desc,
            'long_desc' => $request->long_desc,

            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,

            'product_thumbnail' => $save_url ,
            'status' =>1,
            'created_at' =>Carbon::now(),

        ]);

        $images = $request->file('multi_image');
        foreach($images as $image){
            $make_name = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('upload/products/multi_image/'), $make_name);

            $uploadPath = public_path('upload/products/multi_image'. $make_name);
            // public_path('upload/products/multi_image'.$make_name);

            MultiImage::insert([
                'product_id' => $product_id,
                'photo_name' =>$uploadPath,
                'created_at' =>Carbon::now(),

            ]);
        } //end foreach
        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification);


    } // end method

    public function EditProduct($id){
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategory = SubCategory::latest()->get();
        $products = Product::findOrFail($id);
         return view('backend.product.product_edit', compact('brands', 'categories', 'subcategory', 'products'));
    } // end method


    public function UpdateProduct(Request $request){
        $product_id = $request->id;

                 Product::findOrFail($product_id)->update([

            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,

            'product_name' => $request->product_name,
            'product_slug' =>strtolower(str_replace(' ', '_', $request->product_name)),
            'product_code' => $request->product_code,

            'product_qty' => $request->product_qty,
            'product_tags' => $request->product_tags,
            'product_size' => $request->product_size,

            'product_color' => $request->product_color,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,

            'short_desc' => $request->short_desc,
            'long_desc' => $request->long_desc,

            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,


            'status' =>1,
            'created_at' =>Carbon::now(),

        ]);
        $notification = array(
            'message' => 'Product Updated Without Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification);



    } // end method UpdateProduct

    public function UpdateProductThambnail(Request $request){
        $product_id = $request->id;
        $oldImage = $request->old_image;

        $image = $request->file('product_thumbnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/products/thumbnail/'), $name_gen);
        $save_url = 'upload/products/thumbnail/'.$name_gen;

        if(file_exists($oldImage)){
            unlink($oldImage);
        }

        Product::findOrFail($product_id )->update([
            'product_thumbnail' => $save_url,
            'updated_at' => Carbon::now(),
        ]);
        $notification = array(
            'message' => 'Product Image Thambnail Updated  Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end method UpdateProduct

}
