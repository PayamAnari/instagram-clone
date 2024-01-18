<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AllPostsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($post) {
            return [
                'id' => $post->id,
                'text' => $post->text,
                'file' => $post->file,
                'location' => $post->location,
                'created_at' => $post->created_at->format('d F Y'),
                'favorite_posts' => $post->user->favoritePosts ?? [],
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'text' => $comment->text,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'file' => $comment->user->file,
                        ],
                    ];
                }),
                'Likes' => $post->likes->map(function ($like) {
                    return [
                        'id' => $like->id,
                        'user_id' => $like->user_id,
                        'post_id' => $like->post_id,
                    ];

                }),
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'file' => $post->user->file,
                ],
            ];
        });
    }
}
