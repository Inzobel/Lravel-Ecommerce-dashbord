<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCart;
use App\Models\ProductList;
use App\Models\CartOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use DB;

class ProductCartController extends Controller
{
    public function addToCart(Request $request){
        $email = $request->input('email');
        $quantity = $request->input('quantity');
        $product_code = $request->input('product_code');

        $productDetails = ProductList::where('product_code',$product_code)->get();

        $price = $productDetails[0]['price'];
        $special_price = $productDetails[0]['special_price'];

        if($special_price=="na"){
            $total_price = $price*$quantity;
            $unit_price = $price;
        }
        else{
            $total_price = $special_price*$quantity;
            $unit_price = $special_price;
        }

        $result = ProductCart::insert([

            'email' => $email,
            'image' => $productDetails[0]['image'],
            'product_name' => $productDetails[0]['title'],
            'product_code' => $productDetails[0]['product_code'],
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'total_price' => $total_price, 

        ]);

        return $result;

    } // End Method 

    public function CartCount(Request $request){
        $email = Auth::user()->email; // Get the authenticated user's email
        $result = ProductCart::where('email', $email)->count(); // Count only the items for the authenticated user
        return response()->json(['count' => $result]); // Return the result as JSON
        
    } // End Method

    public function CartList(Request $request){

        $email = $request->email;
        $result = ProductCart::where('email',$email)->get();
        return $result;

    } // End Method 

    public function RemoveCartList(Request $request){

        $id = $request->id;
        $result = ProductCart::where('id',$id)->delete();
        return $result;

    }// End Method 

    public function CartItemPlus(Request $request){
        $id = $request->id;
        $quantity = $request->quantity;
        $price = $request->price;
        $newQuantity = $quantity+1;
        $total_price = $newQuantity*$price;
        $result = ProductCart::where('id',$id)->update(['quantity' =>$newQuantity, 'total_price' => $total_price ]);

        return $result;

   }// End Method 

       public function CartItemMinus(Request $request){
        $id = $request->id;
        $quantity = $request->quantity;
        $price = $request->price;
        $newQuantity = $quantity-1;
        $total_price = $newQuantity*$price;
        $result = ProductCart::where('id',$id)->update(['quantity' =>$newQuantity, 'total_price' => $total_price ]);

        return $result;

   }// End Method 

   public function CartOrder(Request $request){

    $city = $request->input('city');
    $paymentMethod = $request->input('payment_method');
    $yourName = $request->input('name');
    $email = $request->input('email');
    $DeliveryAddress = $request->input('delivery_address');
    $invoice_no = $request->input('invoice_no');
    $DeliveryCharge = $request->input('delivery_charge');

    date_default_timezone_set("Africa/Algiers");
    $request_time = date("h:i:sa");
    $request_date = date("d-m-Y");

    $CartList = ProductCart::where('email',$email)->get();

    foreach($CartList as $CartListItem){
        $cartInsertDeleteResult = "";

        $resultInsert = CartOrder::insert([
            'invoice_no' => "Calista".$invoice_no,
            'product_name' => $CartListItem['product_name'],
            'product_code' => $CartListItem['product_code'],
            'quantity' => $CartListItem['quantity'],
            'unit_price' => $CartListItem['unit_price'],
            'total_price' => $CartListItem['total_price'],
            'email' => $CartListItem['email'],
            'name' => $yourName,
            'payment_method' => $paymentMethod,
            'delivery_address' => $DeliveryAddress,
            'city' => $city,
            'delivery_charge' => $DeliveryCharge,
            'order_date' => $request_date,
            'order_time' => $request_time,
            'order_status' => "Pending",
        ]);

        if ($resultInsert==1) {
           $resultDelete = ProductCart::where('id',$CartListItem['id'])->delete();
           if ($resultDelete==1) {
               $cartInsertDeleteResult=1;
           }else{
               $cartInsertDeleteResult=0;
           }
        }

    }
        return $cartInsertDeleteResult;

   }// End Method 

   public function OrderListByUser(Request $request){
    $email = $request->email;
    $result = CartOrder::where('email',$email)->orderBy('id','DESC')->get();
    return $result;

   }// End Method
   
   ///////////////// Order Process From Backend ////////////////

  

   public function allOrders(){
    $orderStatuses = ['Pending', 'Processing', 'Complete'];
    $orders = CartOrder::whereIn('order_status', $orderStatuses)
                        ->orderBy('id', 'DESC')
                        ->get();
    return view('admin.index',compact('orders'));

    } // End Method 

   public function PendingOrder(){
    $orders = CartOrder::where('order_status','Pending')->orderBy('id','DESC')->get();
    return view('backend.orders.pending_orders',compact('orders'));

    } // End Method 

    public function ProcessingOrder(){

        $orders = CartOrder::where('order_status','Processing')->orderBy('id','DESC')->get();
        return view('backend.orders.processing_orders',compact('orders'));

    } // End Method 


        public function CompleteOrder(){

        $orders = CartOrder::where('order_status','Complete')->orderBy('id','DESC')->get();
        return view('backend.orders.complete_orders',compact('orders'));

    } // End Method 

    public function OrderDetails($id){

        $order = CartOrder::findOrFail($id);
        return view('backend.orders.order_details',compact('order'));


    } // End Method 

    public function PendingToProcessing($id){

        CartOrder::findOrFail($id)->update(['order_status' => 'Processing']);
    
         $notification = array(
                'message' => 'Order Processing Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('pending.order')->with($notification);
    
        } // End Method 
    
    
        public function ProcessingToComplete($id){
    
        CartOrder::findOrFail($id)->update(['order_status' => 'Complete']);
    
         $notification = array(
                'message' => 'Order Complete Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('processing.order')->with($notification);
    
        } // End Method 
    

    public function DeleteOrder($id){
        CartOrder::findOrFail($id)->delete();
         $notification = array(
                'message' => 'Order Deleted Successfully',
                'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);
    }//End Method

    public function totalOrdersCount()
    {

    $totalOrders = CartOrder::count(); // Count all orders
    return response()->json(['total_orders' => $totalOrders]);

    }// End Method

    public function totalRevenue()
    {
    $totalRevenue = CartOrder::sum('total_price'); // Sum all total_price fields
    return response()->json(['total_revenue' => $totalRevenue]);

    }// End Method
    
}
