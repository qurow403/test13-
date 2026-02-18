<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Product;
use App\Models\Comment;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('purchase');

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        $products = $query->latest()->get();

        return $products->map(function ($product) {
            return [
                'id' =>$product->id ,
                'name' => $product->name,
                'image' => $product->image,
                'is_sold' => $product->purchase ? true : false,
            ];
        });
    }

    public function mylike(Request $request)
    {
        if (!Auth::check()) {
            return [];
        }

        $user = Auth::user();

        $query = $user->likes()->with('purchase');

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        return $query->latest()->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'image' => $p->image,
                'is_sold' => $p->purchase !== null,
            ];
        });
    }

    public function show($id)
    {
        $product = Product::with([
            'comments.user'
        ])->withCount([
            'likedUsers',
            'comments'
        ])->findOrFail($id);

        $liked = false;

        if (Auth::check()) {
            $liked = Auth::user()
            ->likes()
            ->where('product_id', $id)
            ->exists();
        }

        return response()->json([
            ...$product->toArray(),
            'likes' => $product->liked_users_count,
            'commentsCount' => $product->comments_count,
            'liked_by_me' => $liked,
            'comments' => $product->comments->map(function ($c) {
                return [
                    'id' => $c->id,
                    'body' => $c->body,
                    'user' => $c->user->name,
                    'avatar' => $c->user->avatar
                        ? Storage::url($c->user->avatar)
                        : null,
                ];
            })
        ]);
    }

    public function toggleLike($id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($user->likes()->where('product_id', $id)->exists()) {
            $user->likes()->detach($id);
            $liked = false;
        } else {
            $user->likes()->attach($id);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $product->likedUsers()->count(),
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string|max:255',
        ], [
            'body.required' => 'コメントは必須です',
            'body.max' => 'コメントは255文字以内で入力してください',
        ]);

        $product = Product::findOrFail($id);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'body' => $request->body,
        ]);

        $comment->load('user');

        return response()->json([
            'id' => $comment->id,
            'body' => $comment->body,
            'user' => $comment->user->name,
            'avatar' => $comment->user->avatar
                ? Storage::url($comment->user->avatar)
                : null,
        ]);
    }
}
