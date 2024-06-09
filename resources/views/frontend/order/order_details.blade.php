@extends('dashboard')
@section('user')


<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
            <span></span> Order page
        </div>
    </div>
</div>
<div class="page-content pt-50 pb-50">
<div class="container">
<div class="row">
<div class="col-lg-12 m-auto">
    <div class="row">

{{-- //Start Col md 3 menu --}}

@include('frontend.body.dashboard_sidebar_menu')

{{-- //End Col md 3 menu --}}


        {{-- // Start Col Md 9 --}}
        <div class="col-md-9">
            <div class="row">

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h4>Shipping Details</h4></div>
                        <hr>
                        <div class="card-body">
                            <table class="table" style="background: #F4F6FA; font-weight:600;">
                                <tr>
                                    <th>Shipping Name:</th>
                                    <th>{{ $order->name }}</th>
                                </tr>
                                <tr>
                                    <th>Shipping Phone:</th>
                                    <th>{{ $order->phone }}</th>
                                </tr>
                                <tr>
                                    <th>Shipping Email:</th>
                                    <th>{{ $order->email }}</th>
                                </tr>
                                <tr>
                                    <th>Shipping Address:</th>
                                    <th>{{ $order->adress }}</th>
                                </tr>
                                <tr>
                                    <th>Division:</th>
                                    <th>{{ $order->division->division_name }}</th>
                                </tr>
                                <tr>
                                    <th>Distract:</th>
                                    <th>{{ $order->district->district_name }}</th>
                                </tr>
                                <tr>
                                    <th>State:</th>
                                    <th>{{ $order->state->state_name}}</th>
                                </tr>
                                <tr>
                                    <th>Post Code:</th>
                                    <th>{{ $order->post_code }}</th>
                                </tr>
                                <tr>
                                    <th>Order Data:</th>
                                    <th>{{ $order->order_date }}</th>
                                </tr>

                            </table>

                        </div>


                    </div>
                </div>
        {{-- // End col-md-6 --}}


        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Order Details
                <span class="text-danger">Invoice: {{ $order->invoice_no }}</span></div></h4>
                <hr>
                <div class="card-body">
                    <table class="table" style="background: #F4F6FA; font-weight:600;">
                        <tr>
                            <th>Name:</th>
                            <th>{{ $order->user->name }}</th>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <th>{{ $order->user->phone }}</th>
                        </tr>
                        <tr>
                            <th>Payment Type:</th>
                            <th>{{ $order->payment_method }}</th>
                        </tr>
                        <tr>
                            <th>Tranx ID:</th>
                            <th>{{ $order->transaction_id }}</th>
                        </tr>
                        <tr>
                            <th>Invoice:</th>
                            <th class="text-danger">{{ $order->invoice_no }}</th>
                        </tr>
                        <tr>
                            <th>Order Amount:</th>
                            <th>${{ $order->amount }}</th>
                        </tr>
                        <tr>
                            <th>Order Status:</th>
                            <th> <span class="badge rounded-pill bg-warning">{{ $order->status }}</span></th>
                        </tr>

                    </table>

                </div>


            </div>
        </div>

            {{-- // End col-md-6 --}}
            </div> {{-- // End row --}}
            
            </div>
        {{-- // End Col Md 9 --}}



        </div>
    </div>
</div>
</div>
</div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table" style="font-weight: 600;">
                    <tbody>
                        <tr>
                            <td class="col-md-1">
                                <label>Image</label>
                            </td>
                            <td class="col-md-2">
                                <label>Product Name</label>
                            </td>
                            <td class="col-md-2">
                                <label>Vendor Name</label>
                            </td>
                            <td class="col-md-2">
                                <label>Product Code</label>
                            </td>
                            <td class="col-md-1">
                                <label>Color</label>
                            </td>
                            <td class="col-md-1">
                                <label>Size</label>
                            </td>
                            <td class="col-md-1">
                                <label>Quantity</label>
                            </td>
                            <td class="col-md-3">
                                <label>Price</label>
                            </td>
                            
                        </tr>


                    @foreach ($orderItem as $item)
                            
                        <tr>
                            <td class="col-md-1">
                                <label><img src="{{ asset($item->product->product_thumbnail) }}" style="width: 50px;height:50px;"></label>
                            </td>

                            <td class="col-md-2">
                                <label>{{ $item->product->product_name }}</label>
                            </td>

                            @if($item->vendor_id == NULL)
                            <td class="col-md-2">
                                <label>Owner</label>
                            </td>
                            @else 
                            <td class="col-md-2">
                                <label>{{ $item->product->vendor->name }}</label>
                            </td>
                            @endif
                            


                            <td class="col-md-2">
                                <label>{{ $item->product->product_code }}</label>
                            </td>

                            @if ($item->color == NULL)
                            <td class="col-md-1">
                                <label>...</label>
                            </td>
                            @else  
                            <td class="col-md-1">
                                <label>{{ $item->color }}</label>
                            </td>
                            @endif
                           
                            @if ($item->size == NULL)
                            <td class="col-md-1">
                                <label>...</label>
                            </td>
                            @else  
                            <td class="col-md-1">
                                <label>{{ $item->size }}</label>
                            </td>
                            @endif
                            <td class="col-md-1">
                                <label>{{ $item->qty }}</label>
                            </td>
                            <td class="col-md-3">
                                <label>${{ $item->price }} <br> Total = ${{ $item->price * $item->qty}} </label>
                            </td>
                            
                        </tr>
                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Start Return Order Option --}}

        @if ($order->status !== 'deliverd')
        @else   
       
        <div class="form-group">

            <label>Order Return Reason</label>
            <textarea name="return_reason" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn-sm btn-danger">Order Return</button>
        @endif
        {{-- End Return Order Option --}}



    </div>
</div>

@endsection
