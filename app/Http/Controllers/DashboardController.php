<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // PASTIKAN TIDAK ADA SPASI ANTARA 'dashboard' DAN '()'
    public function dashboard() 
    {
        // PASTIKAN VIEW INI ADA: resources/views/dashboard/index.blade.php
        return view('dashboard.index');
    }
}
// PASTIKAN TIDAK ADA KARAKTER DI LUAR TAG PENUTUP PHP 