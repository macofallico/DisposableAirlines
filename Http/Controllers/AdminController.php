<?php

namespace Modules\DisposableAirlines\Http\Controllers;

use App\Contracts\Controller;

class AdminController extends Controller
{
    // Admin Page
    public function admin()
    {
        return view('DisposableAirlines::admin');
    }

}
