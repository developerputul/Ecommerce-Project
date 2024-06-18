<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function StoreReview(Request $request){

        $product = $request->product_id;
        $vendor = $request->hvendor_id;

        $request->validate([
            'comment' => 'required',
        ]);
        Review::insert([
            'product_id' => $product,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'rating' => $request->quality,
            'vendor_id' => $request-> $vendor,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Review Will Approved By Admin',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    } // End Method


    public function PendingReview(){

        $review = Review::where('status',0)->orderBy('id', 'DESC')->get();
        return view('backend.review.pending_view',compact('review'));

    } // End Method
}
