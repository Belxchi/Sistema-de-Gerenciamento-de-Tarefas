<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        return Task::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $task = new Task();
        $task->title = $request->input('title');
        $task->description = $request->input('description', '');
        $task->user_id = Auth::id();
        $task->save();

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->title = $request->input('title', $task->title);
        $task->description = $request->input('description', $task->description);
        $task->save();

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }
}
