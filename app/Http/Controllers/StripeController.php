<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class StripeController extends Controller
{

    // 決済完了ページ表示
    public function showSettled()
    {
        return view('settled');
    }

    // 決済ページ
    public function charge(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));//シークレットキー

        $charge = Charge::create(
            array(
                'amount' => 1000,
                'currency' => 'jpy',
                'source' => request()->stripeToken,
            )
        );
        return redirect()->route('settled.show');
    }
}
