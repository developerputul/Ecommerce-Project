<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MultiImage;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\User;
use Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    public function VendorAllProduct(){
        $id = Auth::user()->id;
        $products = Product::where('vendor_id', $id)->latest()->get();
        return view('vendor.backend.product.vendor_product_all', compact('products'));
    } // end method

    public function VendorAddProduct(){
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
         return view('vendor.backend.product.vendor_product_add', compact('brands', 'categories'));
     } // end method

     public function VendorGetSubCategory($category_id){
        $subcat = SubCategory::where('category_id', $category_id)->orderBy('subcategory_name', 'ASC')->get();
        return json_encode($subcat);
    } // end method

    public function VendorStoreProduct(Request $request){
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
            'vendor_id' =>Auth::user()->id,
            'status' =>1,
            'created_at' =>Carbon::now(),

        ]);

        $images = $request->file('multi_image');
        foreach($images as $image){
            $make_name = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('upload/products/multi_image/'), $make_name);

            $uploadPath = 'upload/products/multi_image/'. $make_name;
            // public_path('upload/products/multi_image'.$make_name);

            MultiImage::insert([
                'product_id' => $product_id,
                'photo_name' =>$uploadPath,
                'created_at' =>Carbon::now(),

            ]);
        } //end foreach
        $notification = array(
            'message' => 'Vendor Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.all.product')->with($notification);

    } // end method

    public function VendorEditProduct($id){
        $multiImages = MultiImage::where('product_id', $id)->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategory = SubCategory::latest()->get();
        $products = Product::findOrFail($id);
         return view('vendor.backend.product.vendor_product_edit', compact('brands', 'categories', 'subcategory', 'products', 'multiImages'));
    } // end method

    public function VendorUpdateProduct(Request $request){
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
            'message' => 'Vendor Product Updated Without Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.all.product')->with($notification);

    } // end method UpdateProduct

    public function VendorUpdateProductThambnail(Request $request){
        $product_id = $request->id;
        $oldImage = $request->old_image;

        $image = $request->file('product_thumbnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/products/thumbnail/'), $name_gen);
        $save_url = '/upload/products/thumbnail/'.$name_gen;

        if(file_exists($oldImage)){
            unlink($oldImage);
        }

        Product::findOrFail($product_id )->update([
            'product_thumbnail' => $save_url,
            'updated_at' => Carbon::now(),
        ]);
        $notification = array(
            'message' => 'Vendor Product Image Thambnail Updated  Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end method UpdateProduct

    //vendor product Multipart update
    public function VendorUpdateProductMultiImage(Request $request){
        $images = $request->multi_image;

        foreach($images as $id => $image){
          $imageDelete = MultiImage::findOrfail($id);
          unlink($imageDelete->photo_name);

          $make_name = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
          $image->move(public_path('upload/products/multi_image/'), $make_name);

          $uploadPath = 'upload/products/multi_image/'. $make_name;

          MultiImage::where('id', $id)->update([
            'photo_name' => $uploadPath,
            'updated_at' => Carbon::now(),
          ]);
        } // end foreach
        $notification = array(
          'message' => 'Vendor Product Multi Image Updated  Successfully',
          'alert-type' => 'success'
      );

      return redirect()->back()->with($notification);
  } // end method

  public function VendorMultiImageDelete($id){

    $oldImage = MultiImage::findOrFail($id);
    unlink($oldImage->photo_name);

    MultiImage::findOrFail($id)->delete();

    $notification = array(
        'message' => 'Vendor Product Mult Image Deleted  Successfully',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);

} // end method

public function VendorProductInactive($id){

    Product::findOrFail($id)->update(['status' => 0]);
    $notification = array(
        'message' => 'Vendor Product Inactive',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);

} // end method

public function VendorProductActive($id){

    Product::findOrfail($id)->update(['status' => 1]);

    $notification = array(
        'message' => 'Vendor Product Active',
        'alert-type' => 'success',
    );

    return redirect()->back()->with($notification);

} // end method

public function VendorProductDelete($id){

    $product = Product::findOrFail($id);
    @unlink($product->product_thumbnail);
    $product->delete();

    $images = MultiImage::where('product_id', $id)->get();
    foreach($images as $image){
        @unlink($image->photo_name);
        MultiImage::where('product_id', $id)->delete();
    }

    $notification = array(
        'message' => 'Vendor Product Deleted successfully',
        'alert-type' => 'success',
    );

    return redirect()->back()->with($notification);

} // end method


}
