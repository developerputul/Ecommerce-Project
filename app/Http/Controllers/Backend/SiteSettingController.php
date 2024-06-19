<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Seo;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\SeoSetting;
use Image;


class SiteSettingController extends Controller
{
    public function SiteSetting(){

        $setting = SiteSetting::find(1);
        return view('backend.setting.setting_update',compact('setting'));
    }// End Method

    public function SiteSettingUpdate(Request $request){

        $setting_id = $request->id;
       

        if($request->file('logo')){

        $image = $request->file('logo');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/logo_image'), $name_gen);
        $save_url = 'upload/logo_image/'.$name_gen;

        SiteSetting::findOrFail($setting_id)->update([
            'support_phone' => $request->support_phone,
            'phone_one' => $request->phone_one,
            'email' => $request->email,
            'company_address' => $request->company_address,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'youtube' => $request->youtube,
            'copyright' => $request->copyright,
            'logo' => $save_url,
        ]);

       $notification = array(
            'message' => 'Site Setting Updated With Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('site.setting')->with($notification);

        }
        else{

           SiteSetting::findOrFail($setting_id)->update([
            'support_phone' => $request->support_phone,
            'phone_one' => $request->phone_one,
            'email' => $request->email,
            'company_address' => $request->company_address,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'youtube' => $request->youtube,
            'copyright' => $request->copyright,
          
            ]);

           $notification = array(
                'message' => 'Site Setting Updated Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('site.setting')->with($notification);
        } // End else

    } // End Method


    ///////Seo Setting Method///////

    public function SeoSetting(){

        $seo = Seo::find(1);
        return view('backend.seo.seo_update',compact('seo'));
    }// End Method

    public function SeoSettingUpdate(Request $request){

        $seo_id = $request->id;

        Seo::findOrFail($seo_id)->update([
            'meta_title' => $request->meta_title,
            'meta_auto' => $request->meta_auto,
            'meta_keyword' => $request->meta_keyword,
            'meta_description' => $request->meta_description,
          
            ]);

           $notification = array(
                'message' => 'Seo Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

    } // End Method
}
