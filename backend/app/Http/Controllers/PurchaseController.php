<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\Purchase;

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

        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            ...$data,
        ]);

        return response()->json($purchase);
    }
}
