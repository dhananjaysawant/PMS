<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($task_id)
    {
        $task = Task::findOrFail($task_id);
        $comments = $task->comments()->with('user')->get();

        return response()->json($comments);
    }

    public function store(Request $request, $task_id)
    {
        $request->validate([
            'body' => 'required|string|max:500',
        ]);

        $comment = Comment::create([
            'body' => $request->body,
            'task_id' => $task_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment
        ], 201);
    }
}
