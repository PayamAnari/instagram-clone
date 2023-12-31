<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = new Post();
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png',
            'text' => 'required',
        ]);
        $post = (new FileService)->updateFile($post, $request, 'post');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
