<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Carbon\Carbon;

class SubCategoryController extends Controller
{
    public function AllSubCategory(){
        $subcategories = SubCategory::latest()->get();
        return view('backend.subcategory.subcategory_all', compact('subcategories'));
    } // end method

    public function AddSubCategory(){
        $subcategories =Category::orderBy('category_name', 'ASC')->get();
        return view('backend.subcategory.subcategory_add', compact('subcategories'));
    } // end method

    public function StoreSubCategory(Request $request){

        SubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-',$request->subcategory_name)),
            'created_at' => Carbon::now(),

        ]);

       $notification = array(
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.subcategory')->with($notification);

    }// End Method

    public function EditSubCategory($id){
        $subcategories =Category::orderBy('category_name', 'ASC')->get();
        $subcategory = SubCategory::findOrFail($id);
        return view('backend.subcategory.subcategory_edit', compact('subcategories', 'subcategory'));
    } //end Method

    public function UpdateSubCategory(Request $request){
            $subcategory_id = $request->id;

            SubCategory::findOrFail($subcategory_id)->update([
                'category_id' => $request->category_id,
                'subcategory_name' => $request->subcategory_name,
                'subcategory_slug' => strtolower(str_replace(' ', '-',$request->subcategory_name)),
                'created_at' => Carbon::now(),

            ]);

           $notification = array(
                'message' => 'SubCategory Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.subcategory')->with($notification);
    } // End Method

    public function DeleteSubCategory($id){

        SubCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'SubCategory Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method

}
