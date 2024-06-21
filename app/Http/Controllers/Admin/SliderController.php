<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSlider;

class SliderController extends Controller
{
    public function AllSlider() {
        $result = HomeSlider::all();
        return $result;
    }

    public function GetAllSlider(){
        $slider = HomeSlider::latest()->get();
        return view('backend.slider.slider_view',compact('slider'));
    } // End Mehtod 


    public function AddSlider(){

         return view('backend.slider.slider_add');

    }// End Mehtod 

    public function StoreSlider(Request $request)
{
    // Validate the image
    $request->validate([
        'slider_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ], [
        'slider_image.required' => 'Upload Slider Image'
    ]);

    // Check if the image is present
    if ($request->has('slider_image')) {
        // Store the image and get the path
        $imagePath = $request->file('slider_image')->store('slider', 'public');

        // Generate the save URL
        $save_url = url('http://127.0.0.1:8000/storage/' . $imagePath);

        // Insert the image path into the database
        HomeSlider::insert([
            'slider_image' => $save_url,
        ]);

        // Prepare notification
        $notification = array(
            'message' => 'Slider Inserted Successfully',
            'alert-type' => 'success'
        );

        // Redirect with notification
        return redirect()->route('all.slider')->with($notification);
    }

    // Handle the case where the image is not present (although validation should catch this)
    return redirect()->route('all.slider')->withErrors(['slider_image' => 'Slider image is required']);
    }

    public function EditSlider($id){
        $slider = HomeSlider::findOrFail($id);
        return view('backend.slider.slider_edit',compact('slider'));

    } // End Mehtod 

    public function UpdateSlider(Request $request)  
    {
    $slider_id = $request->id;

    // Validate the image
    $request->validate([
        'slider_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ], [
        'slider_image.required' => 'Upload Slider Image'
    ]);

    // Check if the image is present
    if ($request->has('slider_image')) {
        // Store the image and get the path
        $imagePath = $request->file('slider_image')->store('slider', 'public');

        // Generate the save URL
        $save_url = url('storage/' . $imagePath);

        // Update the image path in the database
        HomeSlider::findOrFail($slider_id)->update([
            'slider_image' => $save_url,
        ]);

        // Prepare notification
        $notification = array(
            'message' => 'Slider Updated Successfully',
            'alert-type' => 'success'
        );

        // Redirect with notification
        return redirect()->route('all.slider')->with($notification);
    }

    // Handle the case where the image is not present
    return redirect()->route('all.slider')->withErrors(['slider_image' => 'Slider image is required']);
    }

    public function DeleteSlider($id){

        HomeSlider::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Mehtod 



}