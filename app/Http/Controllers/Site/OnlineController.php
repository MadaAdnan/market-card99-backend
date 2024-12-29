<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Server;
use Illuminate\Http\Request;

class OnlineController extends Controller
{
    public function index()
    {
        return view('site.online');
    }

    public function programs(Server $server, Country $country)
    {
        return view('site.program', compact('server', 'country'));
    }

    public function orders(){
        return view('site.order_online');
    }
}
