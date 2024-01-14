<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllPostsCollection;
use App\Models\Post;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;



class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user === null) { return redirect()->route('home.index'); }

        $posts = Post::where('user_id', $id)->orderBy('created_at', 'desc')->get();

        return Inertia::render('User', [
            'user' => $user,
            'postsByUser' => new AllPostsCollection($posts)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([ 'file' => 'required|mimes:jpg,jpeg,png' ]);
        $user = (new FileService)->updateFile(auth()->user(), $request, 'user');
        $user->save();

        return redirect()->route('users.show',['id' => auth()->user()->id]);
    }


    public function getFavoritePosts()
    {
        $user = Auth::user();
        return response()->json(['favorite_posts' => $user->favoritePosts ?? []]);
    }

    public function updateFavoritePosts(Request $request)
    {
        $request->validate([
            'post_id' => 'required',
        ]);
    
        $user = Auth::user();
        $favoritePosts = $user->favoritePosts ?? [];
        
        // Fetch the post information based on the post_id
        $post = Post::find($request->post_id);
    
        if ($post && !in_array(['post_id' => $post->id, 'file' => $post->file, 'comments' => $post->comments, 'likes' => $post->likes], $favoritePosts, true)) {
            $favoritePosts[] = [
                'post_id' => $post->id,
                'file' => $post->file,
                'comments' => $post->comments,
                'likes' => $post->likes,
            ];
        
            $user->favoritePosts = $favoritePosts;
        
            $user->save();
        
            return Redirect::back()->with('success', 'Favorite post added successfully');
        }
    
        return Redirect::back()->with('error', 'Favorite post already exists');
    }
    
    
    
public function removeFavoritePost($postId)
{
    $user = Auth::user();

    $favoritePosts = $user->favoritePosts ?? [];

    $postIdToRemove = is_array($postId) ? $postId['post_id'] : $postId;

    $key = array_search($postIdToRemove, array_column($favoritePosts, 'post_id'));

    if ($key !== false) {
        unset($favoritePosts[$key]);

        $favoritePosts = array_values($favoritePosts);

        $user->favoritePosts = $favoritePosts;
        $user->save();
    }

}

}