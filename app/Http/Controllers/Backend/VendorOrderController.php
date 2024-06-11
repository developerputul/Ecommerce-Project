<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\session; 

class VendorOrderController extends Controller
{
    public function VendorOrder(){

        $id = Auth::user()->id;

        $orderitem = OrderItem::where('vendor_id', $id)->orderBy('id', 'DESC')->get();
        return view('vendor.backend.orders.pending_orders',compact('orderitem'));

    } // End Method
}
