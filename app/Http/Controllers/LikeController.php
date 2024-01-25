<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store($postId) 
    {
        $userId = Auth::id();
        
        $like = PostLike::create([
            "user_id" => $userId,
            "post_id" => $postId
        ]);

        $post = Post::where("id", $postId)->with("postLikes")->first();
        $no_of_likes = count($post->postLikes);

        unset( $post->postLikes);
        $post["no_of_likes"] = $no_of_likes;

        return response()->json([
            "data" => $post,
            "message" => "liked successfully",
        ],200);
    }
}
