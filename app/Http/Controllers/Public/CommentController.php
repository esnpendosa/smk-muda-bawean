<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, string $slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        $rules = [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ];

        if (!Auth::check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->parent_id = $request->input('parent_id');
        $comment->content = strip_tags($request->input('content'));
        $comment->status = 'approved'; // Set approved by default, can be moderated later
        $comment->ip_address = $request->ip();
        $comment->user_agent = $request->userAgent();

        if (Auth::check()) {
            $comment->user_id = Auth::id();
        } else {
            $comment->name = $request->input('name');
            $comment->email = $request->input('email');
        }

        $comment->save();

        if ($request->ajax()) {
            // Return the single comment HTML partial or data
            $view = view('public.berita.partials.comment-item', [
                'comment' => $comment,
                'isNew' => true
            ])->render();

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dikirim!',
                'html' => $view
            ]);
        }

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    /**
     * Upvote a comment.
     */
    public function upvote(Request $request, int $id)
    {
        $comment = Comment::where('status', 'approved')->findOrFail($id);
        
        $sessionKey = 'upvoted_comments.' . $comment->id;
        if (session()->has($sessionKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan upvote untuk komentar ini.'
            ], 422);
        }

        $comment->increment('upvotes');
        session()->put($sessionKey, true);

        return response()->json([
            'success' => true,
            'upvotes' => $comment->upvotes,
            'message' => 'Upvote berhasil!'
        ]);
    }
}
