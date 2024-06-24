<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Image;

class SliderController extends Controller
{
    public function AllSlider(){

        $sliders = Slider::latest()->get();
        return view('backend.slider.slider_all', compact('sliders'));

    } // end method AllSlider

    public function AddSlider(){
        return view('backend.slider.slider_add');

    } // end method AddSlider

    public function StoreSlider(Request $request){

        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()). '.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/slider_image'), $name_gen);
        $save_url ='upload/slider_image/'.$name_gen;

        Slider::insert([
            'slider_title' =>$request->slider_title,
            'short_title' =>$request->short_title,
            'slider_image' =>$save_url,
        ]);
        $notification = array(
            'message' =>'Slider Inserted Successfully',
            'alert_type'=>'success'
        );
        return redirect()->route('all.slider')->with($notification);

    } // end method StoreSlider

    public function EditSlider($id){

        $sliders = Slider::findOrFail($id);
        return view('backend.slider.slider_edit', compact('sliders'));

    } // end Method EditSlider

    public function UpdateSlider(Request $request){
        
        $slider_id = $request->id;
        $old_image = $request->old_image;

        if($request->file('slider_image')){

        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/slider_image'), $name_gen);
        $save_url = 'upload/slider_image/'.$name_gen;

        if(file_exists($old_image)){
            unlink($old_image);
        }

        Slider::findOrFail($slider_id)->update([
            'slider_title' =>$request->slider_title,
            'short_title' =>$request->short_title,
            'slider_image' =>$save_url,
        ]);

       $notification = array(
            'message' => 'Slider Updated With Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.slider')->with($notification);

        }
        else{


        Slider::findOrFail($slider_id)->update([
            'slider_title' =>$request->slider_title,
            'short_title' =>$request->short_title,
            ]);

           $notification = array(
                'message' => 'Slider Updated Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.slider')->with($notification);
        } // End else

    } // End Method Update Slider

    public function DeleteSlider($id){

        $slider = Slider::findOrFail($id);
        $image = $slider->slider_image;
        if($image){
            unlink($image);
        }
        $slider->delete();

        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // end Method Delete Slider

}
