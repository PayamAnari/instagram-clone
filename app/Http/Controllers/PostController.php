<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\fileService;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png',
            'text' => 'required',
            'location' => 'required',
        ]);
        $post = new Post();

        $post = (new FileService)->updateFile($post, $request, 'post');

        $post->user_id = auth()->user()->id;
        $post->text = $request->input('text');
        $post->location = $request->input('location');
        $post->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->likes()->delete();
        $post->comments()->delete();

        if (!empty($post->file)) {
            $currentFile = public_path() . $post->file;

            if (file_exists($currentFile)) {
                unlink($currentFile);
            }
        }
        $post->delete();
    }
}
