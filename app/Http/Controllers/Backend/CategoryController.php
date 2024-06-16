<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function AllCategory(){
        $categories = Category::latest()->get();
        return view('backend.category.category_all', compact('categories'));
    } // end method

    public function AddCategory(){
        return view('backend.category.category_add');
    } // end method

    public function StoreCategory(Request $request){

        $image = $request->file('category_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/category_image'), $name_gen);
        $save_url = 'upload/category_image/'.$name_gen;

        Category::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
            'category_image' => $save_url,

        ]);

       $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);

    }// End Method

    public function EditCategory($id){
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit', compact('category'));
    } // End Method
 

    public function UpdateCategory(Request $request){

        $category_id = $request->id;
        $old_image = $request->old_image;

        if($request->file('category_image')){

        $image = $request->file('category_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/category_image'), $name_gen);
        $save_url = 'upload/category_image/'.$name_gen;

        if(file_exists($old_image)){
            unlink($old_image);
        }

        Category::findOrFail($category_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
            'category_image' => $save_url,
        ]);

       $notification = array(
            'message' => 'Category Updated With Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);

        }
        else{
            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),

            ]);

           $notification = array(
                'message' => 'Category Updated Without Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);
        } // End else

    } // End Method

    public function DeleteCategory($id){

        $category = Category::findOrFail($id);
        $image = $category->category_image;
        $relatedProduct = Product::where('category_id', $category->id)->get();
        if($relatedProduct->count() > 0 || $category->category_name == "Sweet Home" || $category->category_name == "Fashion" || $category->category_name == "Mobile"){
            $notification = array(
                'message' => "Category can't be delete cause it's linked to a product !",
                'alert-type' => 'error'
            );
    
            return redirect()->back()->with($notification);
        }
        if($image){
            unlink($image);
        }
        $category->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method
}
