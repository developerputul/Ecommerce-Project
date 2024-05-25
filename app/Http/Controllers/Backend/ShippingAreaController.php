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
}
