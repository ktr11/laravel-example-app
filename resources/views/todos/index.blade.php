<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODOアプリ</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f5f5f5; display: flex; justify-content: center; padding: 40px 16px; }
        .container { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.1); padding: 32px; width: 100%; max-width: 540px; }
        h1 { font-size: 1.6rem; margin-bottom: 24px; color: #333; }
        .add-form { display: flex; gap: 8px; margin-bottom: 24px; }
        .add-form input { flex: 1; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem; }
        .add-form input:focus { outline: none; border-color: #4f8ef7; }
        .btn { padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; font-size: .9rem; }
        .btn-add { background: #4f8ef7; color: #fff; }
        .btn-add:hover { background: #3a7ae0; }
        .todo-list { list-style: none; }
        .todo-item { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .todo-item:last-child { border-bottom: none; }
        .todo-title { flex: 1; font-size: 1rem; color: #333; }
        .todo-title.done { text-decoration: line-through; color: #aaa; }
        .btn-toggle { background: #e8f4e8; color: #2d7a2d; }
        .btn-toggle:hover { background: #c8e8c8; }
        .btn-toggle.undo { background: #f0f0f0; color: #666; }
        .btn-toggle.undo:hover { background: #e0e0e0; }
        .btn-delete { background: #fdecea; color: #c0392b; }
        .btn-delete:hover { background: #f5c5c0; }
        .empty { text-align: center; color: #aaa; padding: 24px 0; }
        .stats { font-size: .85rem; color: #888; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="container">
    <h1>TODO リスト</h1>

    <form action="{{ route('todos.store') }}" method="POST" class="add-form">
        @csrf
        <input type="text" name="title" placeholder="新しいタスクを入力..." autofocus
               value="{{ old('title') }}" required maxlength="255">
        <button type="submit" class="btn btn-add">追加</button>
    </form>

    @if($errors->any())
        <p style="color:#c0392b;margin-bottom:12px;font-size:.9rem;">{{ $errors->first() }}</p>
    @endif

    @if($todos->isNotEmpty())
        <p class="stats">
            {{ $todos->where('completed', true)->count() }} / {{ $todos->count() }} 件完了
        </p>
    @endif

    <ul class="todo-list">
        @forelse($todos as $todo)
            <li class="todo-item">
                <span class="todo-title {{ $todo->completed ? 'done' : '' }}">
                    {{ $todo->title }}
                </span>
                <form action="{{ route('todos.toggle', $todo) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-toggle {{ $todo->completed ? 'undo' : '' }}">
                        {{ $todo->completed ? '元に戻す' : '完了' }}
                    </button>
                </form>
                <form action="{{ route('todos.destroy', $todo) }}" method="POST"
                      onsubmit="return confirm('削除しますか？')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-delete">削除</button>
                </form>
            </li>
        @empty
            <li class="empty">タスクがありません。追加してみましょう！</li>
        @endforelse
    </ul>
</div>
</body>
</html>
