<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use ImageUpload;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $postId)
    {
        $validate = $this->validate($request, [
            "text"=>"nullable",
            "image"=>"file|nullable"
        ]);

        $userId = Auth::id();
        
        // $post = Post::where("id", $postId)->with("comment")->first();
        if($request->hasFile("image")){

            $comment = Comment::create([
                "text" => $validate['text'] ,
                "image" => $this->ImageUpload($validate['image']),
                "user_id" => $userId,
                "post_id" => $postId
            ]);

            $post = Post::where("id", $postId)->with("comment")->first();

            return response()->json([
                "data" => $post,
                "message" => "comment uploaded successfully",
            ],200);
        } else {
            $comment = Comment::create([
                "text" => $validate['text'],
                "user_id" => $userId,
                "post_id" => $postId
            ]);

            $post = Post::where("id", $postId)->with("comment")->first();

            return response()->json([
                "data" => $post,
                "message" => "comment uploaded successfully",
            ],200);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
