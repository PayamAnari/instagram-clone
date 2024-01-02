<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

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
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

  }