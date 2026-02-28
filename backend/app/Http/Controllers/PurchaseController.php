<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\Purchase;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function store(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => '未ログイン'], 400);
        }

        $product = Product::with('purchase')->findOrFail($id);

        if ($product->purchase) {
            return response()->json(['message' => '売り切れです'], 400);
        }

        $data = $request->validate([
            'zip' => 'required',
            'address' => 'required',
            'building' => 'nullable',
            'payment_method' => 'required',
        ]);

        try {

            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => $product->name,
                            ],
                            'unit_amount' => $product->price,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'metadata' => [
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                    ],
                    'success_url' => env('FRONTEND_URL') . '/profile?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => env('FRONTEND_URL') . '/products/' . $product->id,
                ]);

                return response()->json([
                    'url' => $session->url
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'stripe_error' => $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve($request->session_id);

        if ($session->payment_status !== 'paid') {
            return response()->json(['message' => '未決済'], 400);
        }

        $productId = $session->metadata->product_id;
        $userId = $session->metadata->user_id;

        if (!Purchase::where('product_id', $productId)->exists()) {

            try {
                Purchase::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'zip' => 'stripe',
                    'address' => 'stripe',
                    'building' => null,
                    'payment_method' => 'card',
                ]);
            } catch(\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json(['message' => 'ok']);
    }
}
