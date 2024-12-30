<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Menu;
use App\Models\Product;
use App\Models\Review;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function resturantDetails(Client $client)
    {

        if($client->products()->count() < 1) {
            return to_route('index');
        }

        $gallerys = Gallery::where('client_id', $client->id)->latest()->get();
        $menus = Menu::where('client_id', $client->id)->with('products')->get()->filter(function ($menu) {
            return $menu->products->isNotEmpty();
        });

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())  // البحث عن السلة باستخدام user_id
                ->with(['products', 'coupon', 'products.resturant'])  // تحميل العلاقات 'products' و 'coupon' دفعة واحدة
                ->first();  // الحصول على السلة الأولى المطابقة للشروط

            CartController::updateCartTotalPrice($cart);
        }

        $reviews = Review::where('client_id',$client->id)->where('status',1)->get();

        $totalReviews = $reviews->count();
        $ratingSum = $reviews->sum('rating');
        $averageRating = $totalReviews > 0 ? $ratingSum / $totalReviews : 0;
        $roundedAverageRating = round($averageRating, 1);
   
        $ratingCounts = [
           '5' => $reviews->where('rating',5)->count(),
           '4' => $reviews->where('rating',4)->count(),
           '3' => $reviews->where('rating',3)->count(),
           '2' => $reviews->where('rating',2)->count(),
           '1' => $reviews->where('rating',1)->count(),
        ];
        $ratingPercentages =  array_map(function ($count) use ($totalReviews){
           return $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
        },$ratingCounts);

        return view('frontend.dashboard.details_page', get_defined_vars());
    }

    public function addWishList(Request $request, $id)
    {
        if (Auth::check()) {
            $exists = Wishlist::where('user_id', Auth::id())->where('client_id', $id)->first();
            if (!$exists) {
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'client_id' => $id,
                    'created_at' => Carbon::now(),
                ]);
                return response()->json(['success' => 'Your Wishlist Addedd Successfully']);
            } else {
                return response()->json(['error' => 'This product has already on your wishlist']);
            }
        } else {
            return response()->json(['error' => 'First Login Your Account']);
        }
    }
    //End Method

    public function allWishlist()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->with('client')->get();
        $user = Auth::user();

        return view('frontend.dashboard.all_wishlist', get_defined_vars());
    }
    //End Method

    public function removeWishlist($id)
    {
        Wishlist::find($id)->delete();

        $notification = array(
            'message' => 'Wishlist Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
