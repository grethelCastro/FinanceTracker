<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reportes');
    }

    public function profile()
    {
        return view('perfil');
    }
}