<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    function needToDelivery()
    {
        $verify = $this->authverify();
        if(!$verify)
        {
            return response()->json(['message'=>'please verified'],401);
        }
        //get current user
        $current_user =  auth()->user();
        $order_detail = Order::orderBy('id','DESC')->select('customer_id as customer','total','date',
        'status','delivery_status','address_id as address')->where(array('driver_id'=>$current_user->id,'delivery_status'=>'pending'))->get();
        foreach($order_detail as $od)
        {
            $customers = User::orderBy('id','DESC')->select('name','phone','email')->where('id',$od->customer)->first();
            $od->customer = $customers;
            $address = DB::table('address')->orderBy('id','DESC')->where('id',$od->address)->first();
            $od->address = $address;
        }
        return response()->json($order_detail);
     }

     function needToPickup()
     {
         $verify = $this->authverify();
         if(!$verify)
         {
             return response()->json(['message'=>'please verified'],401);
         }
         //get current user
         $current_user =  auth()->user();
         $order_detail = Order::orderBy('id','DESC')->select('customer_id as customer','total','date',
         'status','delivery_status','address_id as address')->where(array('driver_id'=>$current_user->id,'delivery_status'=>'pickup'))->get();
         foreach($order_detail as $od)
         {
             $customers = User::orderBy('id','DESC')->select('name','phone','email')->where('id',$od->customer)->first();
             $od->customer = $customers;
             $address = DB::table('address')->orderBy('id','DESC')->where('id',$od->address)->first();
             $od->address = $address;
         }
         return response()->json($order_detail);
      }

     function getOrderDetail()
     {
         $verify = $this->authverify();
         if(!$verify)
         {
             return response()->json(['message'=>'please verified'],401);
         }
         //get current user
         $current_user =  auth()->user();
         $order_detail = Order::orderBy('id','DESC')->select('customer_id as customer','total','date',
         'status','delivery_status','address_id as address')->where('driver_id',$current_user->id)->get();
         foreach($order_detail as $od)
         {
             $customers = User::orderBy('id','DESC')->select('name','phone','email')->where('id',$od->customer)->first();
             $od->customer = $customers;
             $address = DB::table('address')->orderBy('id','DESC')->where('id',$od->address)->first();
             $od->address = $address;
         }
         return response()->json($order_detail);
      }
}
