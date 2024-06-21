<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;



class CategoryController extends Controller
{
    public function AllCategory()
    {
        $categories = Category::all();
        $categoryDetailsArray = [];

        foreach ($categories as $value) {
            $subcategory = Subcategory::where('category_name', $value['category_name'])->get();
            $item = [
                'category_name' => $value['category_name'],
                'category_image' => $value['category_image'],
                'subcategory_name' => $subcategory
            ];
            array_push($categoryDetailsArray, $item);
        }

        return $categoryDetailsArray;
    } // End Method

    public function GetAllCategory()
    {
        $category = Category::latest()->get();
        return view('backend.category.category_view', compact('category'));
    } // End Method

    public function AddCategory()
    {
        return view('backend.category.category_add');
    } // End Method

    public function StoreCategory(Request $request)
    {


        $validated = $request->validate([
            'category_name' => 'required',
            'category_image' => 'required',
        ]);

        if (request()->hasFile('category_image')) {
            $imagePath = request('category_image')->store('category', 'public');
            $validated['category_image'] = $imagePath;
        }

        $save_url = url('http://127.0.0.1:8000/storage/' . $imagePath);

        Category::create([
            'category_name' => $validated['category_name'],
            'category_image'=> $save_url,
        ]);

        $notification = [
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.category')->with($notification);
    } // End Method

    public function EditCategory($id){

        $category = Category::findOrFail($id);
        return view('backend.category.category_edit',compact('category'));

    } //End Method 


    public function UpdateCategory(Request $request)
    {
    $category_id = $request->id;

    if ($request->file('category_image')) {
        // Validate and store the image
        $request->validate([
            'category_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('category_image')->store('category', 'public');

        // Generate the save URL
        $save_url = url('storage/' . $imagePath);

        // Update category with image
        Category::findOrFail($category_id)->update([
            'category_name' => $request->category_name,
            'category_image' => $save_url,
        ]);

        $notification = [
            'message' => 'Category Updated With Image Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.category')->with($notification);
    } else {
        // Update category without image
        Category::findOrFail($category_id)->update([
            'category_name' => $request->category_name,
        ]);

        $notification = [
            'message' => 'Category Updated Without Image Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.category')->with($notification);
    }
    }


    public function DeleteCategory(Request $request){
        $category = Category::findOrFail($request->id);
        $category->delete();
        $notification = [
            'message'=> 'Category detalted successfully',
            'alert-type'=> 'success'
            ];
            return redirect()->route('all.category')->with($notification);

    }

    ///////////// Start Sub Category All Methods. ////////////////


    public function GetAllSubCategory(){
        $subcategory = Subcategory::latest()->get();
             return view('backend.subcategory.subcategory_view',compact('subcategory'));
     
         } //End Method 
     
         public function AddSubCategory(){
     
             $category = Category::latest()->get();
              return view('backend.subcategory.subcategory_add',compact('category'));
         } //End Method
         
         public function StoreSubCategory(Request $request){
            $request->validate([
                'subcategory_name' => 'required',
            ],[
                'subcategory_name.required' => 'Input SubCategory Name'
    
            ]);
            Subcategory::insert([
                'category_name' => $request->category_name,
                'subcategory_name' => $request->subcategory_name,
            ]);
            $notification = array(
                'message' => 'SubCategory Inserted Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.subcategory')->with($notification);
    
        } //End Method 

        public function EditSubCategory($id){

            $category = Category::orderBy('category_name','ASC')->get();
            $subcategory = Subcategory::findOrFail($id);
            return view('backend.subcategory.subcategory_edit',compact('category','subcategory'));
    
        } //End Method 

        public function UpdateSubCategory(Request $request){

            $subcategory_id = $request->id;
    
            Subcategory::findOrFail($subcategory_id)->update([
                'category_name' => $request->category_name,
                'subcategory_name' => $request->subcategory_name,
            ]);
    
            $notification = array(
                'message' => 'SubCategory Updated Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.subcategory')->with($notification);
    
        } //End Method 

        public function DeleteSubCategory($id){

            Subcategory::findOrFail($id)->delete();
             $notification = array(
                'message' => 'SubCategory Deleted Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->back()->with($notification);
    
        } //End Method 

}
