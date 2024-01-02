<?php

namespace App\Http\Controllers;


use App\Http\Resources\AllPostsCollection;
use App\Models\Post;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
  

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user === null) { return redirect()->route('home.index');}

        $posts = Post::where('user_id', $id)->orderBy('create_at', 'desc')->get();

        return Inertia::render('User', [
          'user' => $user,
          'postByUser' => new AllPostsCollection($posts)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([ 'file' => 'required|mimes:jpg,jpeg,png' ]);
        $user = (new fileService)->updateFile(auth()->user(), $request, 'user');
        $user->save();

        return redirect()->route('users.show',['id' => auth()->user()->id]);
    }

  }