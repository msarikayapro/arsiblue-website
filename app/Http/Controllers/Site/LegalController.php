<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LegalController extends Controller
{
    public function kvkk(): View
    {
        return view('site.legal.kvkk');
    }

    public function cerez(): View
    {
        return view('site.legal.cerez');
    }

    public function about(): View
    {
        return view('site.legal.hakkimizda');
    }
}
