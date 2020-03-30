<?php

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->get();
        foreach ($posts as $post){
            $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likesCount'] = count($post->likes);
            $post['selfLike'] = false;
            foreach ($post->likes as $like){
                if ($like->user_id == Auth::user()->id){
                    $post['selfLike'] = true;
                }
            }
        }

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        $post->description = $request->description;

        //Check if post has photo
        if($request->photo != ''){
            $photo = time().'jpg';
            file_put_contents('storage/posts/'.$photo, base64_decode($request->photo));
            $post->photo = $photo;
        }

        $post->save();
        $post->user;

        return response()->json([
            'success' => true,
            'message' => 'Posted',
            'post' => $post
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post)
    {
        return response()->json([$post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        if(Auth::user()->id != $post->user->id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Post edited'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        if(Auth::user()->id != $post->user->id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        //Check if post has photo
        if($post->photo != ''){
            Storage::delete('public/posts/'.$post->photo);
        }
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Post edited'
        ]);
    }
}
