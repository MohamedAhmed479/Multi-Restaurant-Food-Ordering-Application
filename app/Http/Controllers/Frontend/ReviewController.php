<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    public function StoreReview(Request $request)
    {
        $client = $request->client_id;

        $request->validate([
            'comment' => 'required'
        ]);

        Review::insert([
            'client_id' => $client,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'rating' => $request->rating,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Review Will Approlve By Admin',
            'alert-type' => 'success'
        );

        $previousUrl = $request->headers->get('referer');
        $redirectUrl = $previousUrl ? $previousUrl . '#pills-reviews' : route('res.details', ['id' => $client]) . '#pills-reviews';
        return redirect()->to($redirectUrl)->with($notification);
    }
    // End Method 

    public function adminPendingReview()
    {
        $pedingReview = Review::with(['user', 'client', 'likes'])->where('status', 0)->orderBy('id', 'desc')->get();
        return view('admin.review.view_pending_review', compact('pedingReview'));
    }
    // End Method 

    public function adminApproveReview()
    {
        $approveReview = Review::with(['user', 'client', 'likes'])->where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.review.view_approve_review', compact('approveReview'));
    }
    // End Method 

    public function reviewChangeStatus(Request $request)
    {
        $review = Review::find($request->review_id);
        $review->status = $request->status;
        $review->save();
        return response()->json(['success' => 'Status Change Successfully']);
    }
    // End Method 

    public function storeFeedback(Request $request, Review $review)
    {
        if (! Auth::check()) {
            // Redirect back with a success message
            return redirect()->back()->with([
                'message' => 'Please, Login First!', // Success message
                'alert-type' => 'error',  // Type of alert (success)
            ]);
        }

        $request->validate([
            'like' => 'required|boolean',
        ]);

        // تحقق إذا كان المستخدم قد تفاعل مع المراجعة بالفعل
        $existingLike = ReviewLike::where('review_id', $review->id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($existingLike) {
            $existingLike->update(['like' => $request->like]);
            // Redirect back with a success message
            return redirect()->back()->with([
                'message' => 'Your feedback has been changed.', // Success message
                'alert-type' => 'success',  // Type of alert (success)
            ]);
        } else {
            ReviewLike::create([
                'review_id' => $review->id,
                'user_id' => Auth::user()->id,
                'like' => $request->like,
            ]);
        }

        // Redirect back with a success message
        return redirect()->back()->with([
            'message' => 'Your feedback has been recorded.', // Success message
            'alert-type' => 'success',  // Type of alert (success)
        ]);
    }

    public function clientAllReviews()
    {
        $id = Auth::guard('client')->id();
        $allreviews = Review::with(['user', 'client', 'likes'])->where('status', 1)->where('client_id', $id)->orderBy('id', 'desc')->get();
        return view('client.review.view_all_review', compact('allreviews'));
    }
    // End Method 

}
