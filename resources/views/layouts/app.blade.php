{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '家計簿アプリ')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('faviconsekai.png') }}" type="image/png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('faviconsekai.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('faviconsekai.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />    
    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>    
    {{-- Chart.js for graphs --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold">
                    <a href="{{ route('dashboard') }}">💰 家計簿アプリ</a>
                </h1>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-200">ダッシュボード</a>
                    <a href="{{ route('daily_records.index') }}" class="hover:text-blue-200">記録一覧</a>
                    <a href="{{ route('daily_records.create') }}" class="hover:text-blue-200">新規記録</a>
                    
                    {{-- 目標設定リンクの修正 --}}
                    @php
                        $budgetGoal = App\Models\BudgetGoal::where('user_id', Auth::id())->first();
                    @endphp
                    
                    @if($budgetGoal)
                        <a href="{{ route('budget_goals.edit', ['budget_goal' => $budgetGoal->id]) }}" class="hover:text-blue-200">目標設定</a>
                    @else
                        {{-- 目標レコードがない場合は作成してからリダイレクト --}}
                        <a href="{{ route('dashboard') }}" class="hover:text-blue-200">目標設定</a>
                    @endif               
                    
                    {{-- ユーザー情報とログアウト --}}
                    <div class="flex items-center space-x-2 border-l border-blue-500 pl-4">
                        <span class="text-sm">👤 {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="text-sm bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded"
                                    onclick="return confirm('ログアウトしますか？')">
                                ログアウト
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // CSRF Token setup for AJAX
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    @stack('scripts')
</body>
</html>
