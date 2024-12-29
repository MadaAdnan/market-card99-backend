<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(){
        $products=Product::activate()->where('is_discount',true)->orderBy('updated_at','desc')->paginate(25);
        return view('site.offers',compact('products'));
    }
}
