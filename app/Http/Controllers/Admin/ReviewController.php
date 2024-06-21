<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;

class ReviewController extends Controller
{
    public function ReviewList(Request $request){

        $product_code = $request->product_code;
        $result = ProductReview::where('product_code',$product_code)->orderBy('id','desc')->limit(4)->get();
        return $result;
    } // End Method 

    public function PostReview(Request $request){

        $product_name = $request->input('product_name');
        $product_code = $request->input('product_code');
        $user_name = $request->input('reviewer_name');
        $reviewer_photo = $request->input('reviewer_photo');
        $reviewer_rating = $request->input('reviewer_rating');
        $reviewer_comments = $request->input('reviewer_comments');
    
        // Validate input data
        if (empty($product_name) || empty($product_code) || empty($user_name) || empty($reviewer_rating) || empty($reviewer_comments)) {
            return response()->json(['error' => 'All fields are required.'], 400);
        }
    
        try {
            // Insert review into database
            $result = ProductReview::insert([
                'product_name' => $product_name,
                'product_code' => $product_code,
                'reviewer_name' => $user_name,
                'reviewer_photo' => $reviewer_photo,
                'reviewer_rating' => $reviewer_rating,
                'reviewer_comments' => $reviewer_comments,
            ]);
    
            // Check if insertion was successful
            if ($result) {
                return response()->json(['success' => 'Review posted successfully.'], 201);
            } else {
                return response()->json(['error' => 'Failed to post review.'], 500);
            }
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'An error occurred while posting review.'], 500);
        }
    }

    public function GetAllReview(){

        $review = ProductReview::latest()->get();
       return view('backend.review.review_all', compact('review'));
   }// End Method 

   public function DeleteReview($id)
   {
       try {
           // Find the review by ID
           $review = ProductReview::findOrFail($id);

           // Delete the review
           $review->delete();

           // Return response
           $notification = [
               'message' => 'Review Deleted Successfully',
               'alert-type' => 'success'
           ];

           return redirect()->route('all.review')->with($notification);
       } catch (\Exception $e) {
           // Handle any exceptions
           return response()->json(['error' => 'An error occurred while deleting the review.'], 500);
       }
   }// End method

    public function getTotalReviews()
    {
        $total_reviews = ProductReview::count();
        return response()->json(['total_reviews' => $total_reviews]);
    }// End Method
}
