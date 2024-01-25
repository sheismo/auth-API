<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use ImageUpload;
    //
    public function store(Request $request) {
        $userId= Auth::id();
        $validate= $this->validate($request, [
            "text" => "nullable",
            "image" =>"file|nullable"
        ]);

        if($request->hasFile("image")){

            $post = Post::create([
            "text" => $validate['text'] ,
            "image" => $this->ImageUpload($validate['image']),
            "user_id" => $userId,
            ]);

            return response()->json([
                "data"=>$post,
                "message"=> "post uploaded successfully",
            ],200);
        } 
        else 
        {
            $post = Post::create([
            "text" => $validate['text'] ,
            "user_id" => $userId,
            ]);

            return response()->json([
                "data" => $post,
                "message" => "post uploaded successfully",
            ],200);
        }
        
    }

    public function index ()
    {
        // $userId= Auth::id();
        $posts = Post::all();
        
        if($posts) 
        {
            return response()->json([
            "data"=> $posts,
            "message"=> "All available posts"
        ], 200);
        } 
        else 
        {
            return response()->json([
                "message"=>"Error occurred, can't retrieve posts"
            ], 200);
        }

    }

    public function show($id)
    {
        $post = Post::where("id",$id)->first();

        if(!$post){
            return response()->json(["message" => "Post is not available"], 400);
        }

        return response()->json([
            "data" => $post,
            "status"=> "ok"
        ],200);
    }

    public function getMyPosts()
    {
        $userId = Auth::id();
        
        $posts = Post::where("user_id",$userId)->orderBy("created_at")->get();

        return response()->json([
            "data"=> $posts,
            "message"=> "Your posts"
        ], 200);
    }
}
