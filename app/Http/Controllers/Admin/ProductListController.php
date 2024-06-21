<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductList;
use App\Models\ProductDetails;
use App\Models\Category;
use App\Models\Subcategory;
 

class ProductListController extends Controller
{
    public function ProductListByRemark(Request $request){

        $remark = $request->remark;
        $productlist = ProductList::where('remark',$remark)->get();
        return $productlist; 
    }
    
    public function ProductListByCategory(Request $request){

        $Category = $request->category;
        $productlist = ProductList::where('category', $Category)->get();
        return $productlist;
    }

    public function ProductListBySubCategory(Request $request){

        $Category = $request->category;
        $SubCategory = $request->subcategory;
        $productlist = ProductList::where('category', $Category)->where('subcategory', $SubCategory)->get();
        return $productlist;
    }
    
    public function ProductBySearch(Request $request){
        $key = $request->key;
        $productlist = ProductList::where('title','LIKE',"%{$key}%")->get();
        return $productlist;
    }

    public function SimilarProduct(Request $request){
        $subcategory = $request->subcategory;
        $productlist = ProductList::where('subcategory',$subcategory)->orderBy('id','desc')->limit(6)->get();
        return $productlist;

    }// End Method 

    public function ProductList(Request $request){
        
        $productlist = ProductList::latest('id')->get() ;
        return $productlist;
    }

    public function GetAllProduct(){

        $products = ProductList::latest()->paginate(10);
        return view('backend.product.product_all',compact('products'));

    } // End Method
    
    public function AddProduct(){

        $category = Category::orderBy('category_name','ASC')->get();
        $subcategory = Subcategory::orderBy('subcategory_name','ASC')->get();
        return view('backend.product.product_add',compact('category','subcategory'));

    } // End Method 

    public function StoreProduct(Request $request)
    {
    $request->validate([
        'product_code' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ], [
        'product_code.required' => 'Input Product Code'
    ]);

    // Store the main product image and get the path
    $imagePath = $request->file('image')->store('product', 'public');

    // Generate the save URL for the main product image
    $save_url = url('http://127.0.0.1:8000/storage/' . $imagePath);

    // Insert product data into the database
    $product_id = ProductList::insertGetId([
        'title' => $request->title,
        'price' => $request->price,
        'special_price' => $request->special_price,
        'image' => $save_url,
        'category' => $request->category,
        'subcategory' => $request->subcategory,
        'remark' => $request->remark,
        'product_code' => $request->product_code,
        
    ]);

    // Store product detail images
    $imageOne = $request->file('image_one')->store('product','public');
       
    $save_url1 = url('http://127.0.0.1:8000/storage/' . $imageOne);
    

    // Insert product details into the database
    ProductDetails::insert([
        'product_id' => $product_id,
        'image_one' => $save_url1,
        'short_description' => $request->short_description,
        'long_description' => $request->long_description,
    ]);

    $notification = [
        'message' => 'Product Inserted Successfully',
        'alert-type' => 'success'
    ];

    return redirect()->route('all.product')->with($notification);
    }// end of the methode

    public function EditProduct($id){

        $category = Category::orderBy('category_name','ASC')->get();
        $subcategory = Subcategory::orderBy('subcategory_name','ASC')->get();
        $product = ProductList::findOrFail($id);
        $details = ProductDetails::where('product_id',$id)->get();
        return view('backend.product.product_edit', compact('category','subcategory','product','details'));
    }

    public function deleteProduct($id)
    {
        // Find the product by ID
        $product = ProductList::findOrFail($id);

        // Delete associated product details
        ProductDetails::where('product_id', $id)->delete();

        // Delete the product
        $product->delete();

        // Return response
        $notification = [
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.product')->with($notification);
    }

    }
