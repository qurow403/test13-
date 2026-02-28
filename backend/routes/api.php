<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConditionController;

use App\Models\User;
use App\Models\Purchase;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'logged out']);
});

Route::middleware('auth:sanctum')->post('/email/verification-notification',
    function (Request $request) {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'すでにメール認証は完了しています'
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => '認証メールを再送しました'
        ]);
    });

Route::get('/email/verify/{id}/{hash}', function ($id, $hash){
    $user = User::findOrFail($id);

    if (! hash_equals(
        sha1($user->getEmailForVerification()),
        $hash
    )) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    $token = $user->createToken('first-login')->plainTextToken;

    return redirect(
        config('app.frontend_url') .
        '/profile/setup?token=' . $token
    );
})
->middleware(['signed'])
->name('verification.verify');


Route::middleware('auth:sanctum')->post('/profile', function (request $request) {
    $user = $request->user();

    $data = $request->validate([
        'zip' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'building' => 'nullable|string|max:255',
        'avatar' => 'nullable|image|max:2048',
    ], [
        'zip.required' => '郵便番号を入力してください',
        'address.required' => '住所を入力してください',
    ]);

    if ($request->hasFile('avatar')) {
        $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
    }

    $request->user()->update([
        ...$data,
        'profile_completed' =>true,
    ]);

    return response()->json($user);
});


Route::get('/products', [ProductController::class, 'index']);
Route::middleware('auth:sanctum')->get('/products/mylike', [ProductController::class, 'mylike']);

Route::get('/products/{id}', [ProductController::class, 'show']);
Route::middleware('auth:sanctum')->post('/products/{id}/like', [ProductController::class, 'toggleLike']);
Route::middleware('auth:sanctum')->post('/products/{id}/comments', [ProductController::class, 'storeComment']);

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    $user = $request->user();

    return [
        ...$user->toArray(),
        'avatar' => $user->avatar
            ? Storage::url($user->avatar)
            : null,
    ];
});

Route::middleware('auth:sanctum')->post('/products/{id}/purchase', [PurchaseController::class, 'store']);
Route::middleware('auth:sanctum')->post('/purchase/success', [PurchaseController::class, 'success']);

Route::middleware('auth:sanctum')->get('/purchases', function (Request $request) {
    return Purchase::with('product')
        ->where('user_id', $request->user()->id)
        ->get();
});

Route::middleware('auth:sanctum')->post('/products', [ProductController::class, 'store']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/conditions', function (){
    return \App\Models\Condition::orderBy('sort_order')->get();
});
Route::middleware('auth:sanctum')->get('/my-products', function (Request $request){
    return \App\Models\Product::where('user_id', $request->user()->id)->get();
});
