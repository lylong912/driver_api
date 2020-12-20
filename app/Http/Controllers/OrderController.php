<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Package;
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
        $order_detail = Order::orderBy('id','DESC')->select('id as order_id','customer_id as customer','total','date',
        'status','delivery_status','address_id as address')->where(array('driver_id'=>$current_user->id,'delivery_status'=>'pending'))->get();
        foreach($order_detail as $od)
        {
            $packages = Package::orderBy('id','DESC')->where('id',$od->order_id)->get();
            $od->packages = $packages;
            
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
         $order_detail = Order::orderBy('id','DESC')->select('id as order_id','customer_id as customer','total','date',
         'status','delivery_status','address_id as address')->where(array('driver_id'=>$current_user->id,'delivery_status'=>'pickup'))->get();
         foreach($order_detail as $od)
         {
            $packages = Package::orderBy('id','DESC')->where('id',$od->order_id)->get();
            $od->packages = $packages;

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
         $order_detail = Order::orderBy('id','DESC')->select('id as order_id','customer_id as customer','total','date',
         'status','delivery_status','address_id as address')->where('driver_id',$current_user->id)->get();
         foreach($order_detail as $od)
         {
            $packages = Package::orderBy('id','DESC')->where('id',$od->order_id)->get();
            $od->packages = $packages;

             $customers = User::orderBy('id','DESC')->select('name','phone','email')->where('id',$od->customer)->first();
             $od->customer = $customers;
             $address = DB::table('address')->orderBy('id','DESC')->where('id',$od->address)->first();
             $od->address = $address;
         }
         return response()->json($order_detail);
      }
      function getDeliveredList()
      {
          $verify = $this->authverify();
          if(!$verify)
          {
              return response()->json(['message'=>'please verified'],401);
          }
          //get current user
          $current_user =  auth()->user();
          $order_detail = Order::orderBy('id','DESC')->select('id as order_id','customer_id as customer','total','date',
          'status','delivery_status','address_id as address')->where(array('driver_id'=>$current_user->id,'delivery_status'=>'delivered'))->get();
          foreach($order_detail as $od)
          {
              $packages = Package::orderBy('id','DESC')->where('id',$od->order_id)->get();
              $od->packages = $packages;

              $customers = User::orderBy('id','DESC')->select('name','phone','email')->where('id',$od->customer)->first();
              $od->customer = $customers;
              $address = DB::table('address')->orderBy('id','DESC')->where('id',$od->address)->first();
              $od->address = $address;
          }
          return response()->json($order_detail);
       }


      //post 

      function Pickup(Request $request)
      {
          $verify = $this->authverify();
          if(!$verify)
          {
              return response()->json(['message'=>'please verified'],401);
          }
          //get current user
          $current_user =  auth()->user();
          $pickup = Order::orderBy('id','DESC')->where(array('driver_id'=>$current_user->id,'delivery_status'=>'pending','id'=>$request->input('order_id')))->first();
          if($pickup)
          {
            $changeTopickup = Order::orderBy('id','desc')
            ->where('id',$pickup->id)
            ->update(array('delivery_status'=>'pickup'));
            return response()->json(array('message'=>'Success'),200);
          }
          else{
              return response()->json(array('message'=>'No order found'),500);
          }
         
       }
       
      function Delivered(Request $request)
      {
          $verify = $this->authverify();
          if(!$verify)
          {
              return response()->json(['message'=>'please verified'],401);
          }
          //get current user
          $current_user =  auth()->user();
          $deliver = Order::orderBy('id','DESC')->where(array('driver_id'=>$current_user->id,'delivery_status'=>'pickup','id'=>$request->input('order_id')))->first();
          if($deliver)
          {
            $changeToDelivered = Order::orderBy('id','desc')
            ->where('id',$deliver->id)
            ->update(array('delivery_status'=>'delivered'));
            return response()->json(array('message'=>'Success'),200);
          }
          else{
              return response()->json(array('message'=>'No order found'),500);
          }
         
       }
}
