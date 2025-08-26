<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    //---------------------------------- category----------------------------------
    public function index()
    {
        $no_of_order = 0;
        $order = null;
        if (Auth::check()) {
            $cus_id = Auth::user()->id;
            $order = Order::with('customer', 'orderitem')->where('customer_id', $cus_id)->orderBy('id', 'DESC')->get();
            $no_of_order = $order->count();
        }
        return view('frontend.order', compact('order', 'no_of_order'));
    }

    public function view_order($id)
    {
        if (Auth::check()) {
            $cus_id = Auth::user()->id;
            $order = Order::with('customer', 'orderitem')->where('id', Crypt::decrypt($id))->where('customer_id', $cus_id)->first();
            if ($order != null) {
                return view('frontend.show_order', compact('order'));
            } else {
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('dashboard');
    }

    public function cancel_order($id)
    {
        if (Auth::check()) {
            $cus_id = Auth::user()->id;
            $order = Order::with('customer', 'orderitem')->where('id', Crypt::decrypt($id))->where('customer_id', $cus_id)->first();
            if ($order != null) {
                Order::cancelOrder($order->id);
                return redirect()->route('view-order', $id);
            } else {
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('dashboard');
    }

    public function download_invoice($order_id)
    {
        if (Auth::check()) {
            $cus_id = Auth::user()->id;
            $order = Order::with('customer', 'orderitem')->where('id', Crypt::decrypt($order_id))->where('customer_id', $cus_id)->first();
            if ($order != null) {
                Order::generateinvoice(Crypt::decrypt($order_id), true, true);
            } else {
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('dashboard');
    }
}
