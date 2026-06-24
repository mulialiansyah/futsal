<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $myBookings = Booking::with(['lapangan', 'pembayaran'])
                             ->where('user_id', Auth::id())
                             ->latest()
                             ->get();

        return view('customer.home', compact('myBookings'));
    }
}
