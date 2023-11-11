<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller {
    public function index() {
        $posts = Post::latest()->paginate(5);

        return new PostResource(true, 'Success get data', $posts);
    }

    public function store(Request $request) {
        // dump($request->input('title'));
        // define validator
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'content' => 'required'
        ]);


        // check if validator fails
        if($validator->fails()) return response()->json($validator->errors(), 422);

        // upload image
        $image = $request->file('image');
        $image->storeAs('public/post', $image->hashName());

        $post = Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return new PostResource(true, 'Success add a new data', $post);
    }

    public function show($id) {
        if(!is_numeric($id) || $id < 1) return response()->json("Invalid ID", 400);

        $post = Post::find($id);

        return new PostResource(true, 'Success add a new data', $post);
    }
}
