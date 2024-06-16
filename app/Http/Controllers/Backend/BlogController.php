<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Carbon\Carbon;
use Intervention\Image\Image;

class BlogController extends Controller
{
    public function AllBlogCategory(){

        $blogcategories = BlogCategory::latest()->get();
        return view('backend.blog.category.blogcategory_all',compact('blogcategories'));

    } // End Method

    public function AdminAddBlogCategory(){
        return view('backend.blog.category.blogcategory_add');

    } // End Method

    public function AdminStoreBlogCategory(Request $request){

        $request->validate([
            'blog_category_name' => 'required|string|max:255',
        ]);

        BlogCategory::insert([
            'blog_category_name' => $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '-',$request->blog_category_name)),
            'created_at' => Carbon::now(),

        ]);

       $notification = array(
            'message' => ' Blog Category Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.category')->with($notification);
    }// End Method

    public function EditBlogCategory($id){

        $blogcategories = BlogCategory::findOrFail($id);

        return view('backend.blog.category.blogcategory_edit',compact('blogcategories'));

    } // End Method

    public function UpdateBlogCategory(Request $request){

        $blog_id = $request->id;

        $request->validate([
            'blog_category_name' => 'required|string|max:255',
        ]);

        BlogCategory::findOrfail($blog_id)->update([
            'blog_category_name' => $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '-',$request->blog_category_name)),
          
        ]);

       $notification = array(
            'message' => ' Blog Category Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.category')->with($notification);

    }// End Method

    public function DeleteBlogCategory($id){

        BlogCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => ' Blog Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // End Method

    ////////////////Blog Post Method ///////////////////////////

    public function AllBlogPost(){

        $blogpost = BlogPost::latest()->get();
        return view('backend.blog.post.blogpost_all',compact('blogpost'));

    }  //End Method

    public function AddBlogPost(){

        $blogcategory = BlogCategory::latest()->get();
        return view('backend.blog.post.blogpost_add',compact('blogcategory'));
    } // End Method 

    public function StoreBlogPost(Request $request){

        $image = $request->file('post_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/blog'), $name_gen);
        $save_url = 'upload/blog/'.$name_gen;

        BlogPost::insert([
            'category_id' => $request->category_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ', '-',$request->post_title)),

            'post_short_desc' => $request->post_short_desc,
            'post_long_desc' => $request->post_long_desc,
            'post_image' => $save_url,
            'created_at' => Carbon::now(),
        ]);

       $notification = array(
            'message' => 'Blog Post Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.post')->with($notification);
    } // End Method

    public function EditBlogPost($id){

        $blogcategory = BlogCategory::latest()->get();
        $blogpost = BlogPost::findOrFail($id);

        return view('backend.blog.post.blogpost_edit',compact('blogcategory','blogpost'));
    } // End Method

    public function UpdateBlogPost(Request $request){
        
        $post_id = $request->id;
        $old_image = $request->old_image;

        if($request->file('post_image')){

       
            $image = $request->file('post_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('upload/blog'), $name_gen);
            $save_url = 'upload/blog/'.$name_gen;

        if(file_exists($old_image)){
            unlink($old_image);
        }

        BlogPost::findOrFail($post_id)->update([
           'category_id' => $request->category_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ', '-',$request->post_title)),

            'post_short_desc' => $request->post_short_desc,
            'post_long_desc' => $request->post_long_desc,
            'post_image' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

       $notification = array(
            'message' => 'Blog Post Updated With Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.blog.post')->with($notification);

        }
        else{

            BlogPost::findOrFail($post_id)->update([
                'category_id' => $request->category_id,
                 'post_title' => $request->post_title,
                 'post_slug' => strtolower(str_replace(' ', '-',$request->post_title)),
     
                 'post_short_desc' => $request->post_short_desc,
                 'post_long_desc' => $request->post_long_desc,
                 'updated_at' => Carbon::now(),
             ]);
           $notification = array(
                'message' => 'Blog Post Updated Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('admin.blog.post')->with($notification);
        } // End else

    } // End Method

    public function DeleteBlogPost($id){

        $blogpost = BlogPost::findOrFail($id);
        $image = $blogpost->post_image;

        unlink($image);

        BlogPost::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Blog Post Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);


    } // End Method


}
