<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopRepresentativeController extends Controller
{
    public function showShopManagement()
    {
        return view('shop-management');
    }
}
