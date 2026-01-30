<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\AuthController;

use App\Models\User;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/email/verification-notification', function (Request $request) {
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/profile', function (request $request) {
    $request->user()->update([
        'profile_completed' =>true,
    ]);

    return response()->json(['message' => 'profile updated']);
});
