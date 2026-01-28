<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $info_count = \App\Models\Info::count();

        if (Auth::user()->role === 'admin') {
            return view('dashboard.admin', compact('info_count'));
        }

        return view('dashboard.user', compact('info_count'));
    }
}
