<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TodoController extends Controller
{
    public function index(): View
    {
        return view('todos.index', [
            'todos' => Todo::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        Todo::create($data);

        return to_route('todos.index')->with('success', 'TODOを追加しました。');
    }

    public function update(Request $request, Todo $todo): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $todo->update($data);

        return to_route('todos.index')->with('success', 'TODOを更新しました。');
    }

    public function toggle(Todo $todo): RedirectResponse
    {
        $todo->update(['completed' => ! $todo->completed]);

        return to_route('todos.index')->with('success', 'TODOの状態を更新しました。');
    }

    public function destroy(Todo $todo): RedirectResponse
    {
        $todo->delete();

        return to_route('todos.index')->with('success', 'TODOを削除しました。');
    }
}
