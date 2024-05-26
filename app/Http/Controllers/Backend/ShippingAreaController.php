<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDivision;
use App\Models\ShipDistricts;
use App\Models\ShipState;
Use Carbon\Carbon;


class ShippingAreaController extends Controller
{
    public function AllDivision(){

        $division = ShipDivision::latest()->get();
        return view('backend.ship.division.division_all',compact('division'));
     }// End Method

     public function AddDivision(){
        return view('backend.ship.division.division_add');

     } // End Method

     public function StoreDivision(Request $request){

        ShipDivision::insert([
            'division_name' => $request->division_name,
        ]);

       $notification = array(
            'message' => 'ShipDivision Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.division')->with($notification);
    } // ENd Method

    public function EditDivision($id){

        $division = ShipDivision::findOrFail($id);
        return view('backend.ship.division.edit_division', compact('division'));

    }// End Mehtod

    public function UpdateDivision(Request $request){
        $division_id = $request->id;

        ShipDivision::findOrFail($division_id)->update([
           
            'division_name' => $request->division_name,   

        ]);

       $notification = array(
            'message' => 'Division Name Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.division')->with($notification);

    } // End Method

    public function DeleteDivision($id){

        ShipDivision::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Division Name Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method

    /////////////////// District CRUD////////////////////

    public function AllDistrict(){

        $district = ShipDistricts::latest()->get();
        return view('backend.ship.district.district_all',compact('district'));
     }// End Method

     public function AddDistrict(){

        $division = ShipDivision::orderBy('division_name', 'ASC')->get();
        return view('backend.ship.district.district_add',compact('division'));

     } // End Method


     public function StoreDistrict(Request $request){

        ShipDistricts::insert([
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,
        ]);

       $notification = array(
            'message' => 'ShipDistricts Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.district')->with($notification);
    } // ENd Method

    public function EditDistrict($id){

        $division = ShipDivision::orderBy('division_name', 'ASC')->get();
        $district = ShipDistricts::findOrFail($id);
        return view('backend.ship.district.district_edit', compact('district','division'));

    }// End Mehtod

    public function UpdateDistrict(Request $request){

        $district_id = $request->id;
        ShipDistricts::findOrFail($district_id)->update([
           
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,

        ]);

       $notification = array(
            'message' => 'ShipDistricts  Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.district')->with($notification);

    } // End Method

    public function DeleteDistrict($id){

        ShipDistricts::findOrFail($id)->delete();
        $notification = array(
            'message' => 'ShipDistricts Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method

}
