<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TODO管理</title>
    <style>
        :root { color-scheme: light; font-family: system-ui, sans-serif; color: #172033; background: #f3f5f9; }
        body { margin: 0; padding: 3rem 1rem; }
        main { max-width: 720px; margin: auto; }
        h1 { margin-bottom: .25rem; }
        .subtitle { margin-top: 0; color: #5f6878; }
        .card { margin-top: 1.25rem; padding: 1.25rem; border: 1px solid #e0e5ed; border-radius: 12px; background: white; }
        label { display: block; margin: .8rem 0 .3rem; font-weight: 600; }
        input, textarea { box-sizing: border-box; width: 100%; padding: .7rem; border: 1px solid #cbd2de; border-radius: 6px; font: inherit; }
        textarea { min-height: 5rem; resize: vertical; }
        button { padding: .55rem .85rem; border: 0; border-radius: 6px; background: #3157c8; color: white; font: inherit; cursor: pointer; }
        button.danger { background: #a8333b; }
        .todo { display: grid; grid-template-columns: auto 1fr; gap: .8rem; padding: 1rem 0; border-bottom: 1px solid #e8ebf0; }
        .todo:last-child { border-bottom: 0; padding-bottom: 0; }
        .todo form { margin: 0; }
        .todo-title { margin: 0; font-weight: 700; overflow-wrap: anywhere; }
        .completed .todo-title { color: #788191; text-decoration: line-through; }
        .description { margin: .35rem 0 .8rem; color: #5f6878; white-space: pre-wrap; overflow-wrap: anywhere; }
        .actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
        .actions details { flex: 1 1 100%; margin: .3rem 0; }
        .actions summary { color: #3157c8; cursor: pointer; }
        .actions details button { margin-top: .7rem; }
        .notice, .error { padding: .8rem; border-radius: 6px; }
        .notice { background: #e8f6ed; color: #205c35; }
        .error { color: #a8333b; }
        .empty { color: #5f6878; text-align: center; padding: 1.5rem 0; }
        @media (max-width: 520px) { body { padding-top: 1.5rem; } }
    </style>
</head>
<body>
<main>
    <h1>TODO管理</h1>
    <p class="subtitle">やることを整理して、ひとつずつ完了しましょう。</p>

    @if (session('success'))
        <p class="notice" role="status">{{ session('success') }}</p>
    @endif

    <section class="card" aria-labelledby="new-todo-heading">
        <h2 id="new-todo-heading">TODOを追加</h2>
        <form method="POST" action="{{ route('todos.store') }}">
            @csrf
            <label for="new-title">タイトル</label>
            <input id="new-title" name="title" value="{{ old('title') }}" maxlength="255" required>
            @error('title') <p class="error">{{ $message }}</p> @enderror
            <label for="new-description">詳細（任意）</label>
            <textarea id="new-description" name="description" maxlength="5000">{{ old('description') }}</textarea>
            @error('description') <p class="error">{{ $message }}</p> @enderror
            <button type="submit">追加する</button>
        </form>
    </section>

    <section class="card" aria-labelledby="todo-list-heading">
        <h2 id="todo-list-heading">TODO一覧（{{ $todos->count() }}件）</h2>
        @forelse ($todos as $todo)
            <article class="todo {{ $todo->completed ? 'completed' : '' }}">
                <form method="POST" action="{{ route('todos.toggle', $todo) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" aria-label="{{ $todo->completed ? '未完了に戻す' : '完了にする' }}: {{ $todo->title }}">
                        {{ $todo->completed ? '✓' : '○' }}
                    </button>
                </form>
                <div>
                    <p class="todo-title">{{ $todo->title }}</p>
                    @if ($todo->description)
                        <p class="description">{{ $todo->description }}</p>
                    @endif
                    <div class="actions">
                        <details>
                            <summary>編集</summary>
                            <form method="POST" action="{{ route('todos.update', $todo) }}">
                                @csrf
                                @method('PATCH')
                                <label for="title-{{ $todo->id }}">タイトル</label>
                                <input id="title-{{ $todo->id }}" name="title" value="{{ $todo->title }}" maxlength="255" required>
                                <label for="description-{{ $todo->id }}">詳細（任意）</label>
                                <textarea id="description-{{ $todo->id }}" name="description" maxlength="5000">{{ $todo->description }}</textarea>
                                <button type="submit">保存する</button>
                            </form>
                        </details>
                        <form method="POST" action="{{ route('todos.destroy', $todo) }}" onsubmit="return confirm('このTODOを削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button class="danger" type="submit">削除</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <p class="empty">TODOはまだありません。上のフォームから追加してください。</p>
        @endforelse
    </section>
</main>
</body>
</html>
