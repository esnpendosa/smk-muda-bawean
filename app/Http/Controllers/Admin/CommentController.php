<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for moderation.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $query = Comment::with('post', 'user')->orderBy('created_at', 'desc');

        if ($status && in_array($status, ['pending', 'approved', 'spam', 'trash'])) {
            $query->where('status', $status);
        }

        $comments = $query->paginate(15)->withQueryString();

        return view('admin.comments.index', compact('comments', 'status'));
    }

    /**
     * Update the status of a comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,spam,trash'
        ]);

        $comment->update([
            'status' => $request->input('status')
        ]);

        return back()->with('success', 'Status komentar berhasil diperbarui.');
    }

    /**
     * Remove the comment from database.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
