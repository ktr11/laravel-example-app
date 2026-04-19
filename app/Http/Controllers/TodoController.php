<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::latest()->get();
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);
        Todo::create(['title' => $request->title]);
        return redirect()->route('todos.index');
    }

    public function toggle(Todo $todo)
    {
        $todo->update(['completed' => !$todo->completed]);
        return redirect()->route('todos.index');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index');
    }
}
