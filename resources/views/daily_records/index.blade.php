{{-- resources/views/daily_records/index.blade.php --}}
@extends('layouts.app')

@section('title', '記録一覧 - 家計簿アプリ')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">📝 記録一覧</h1>
    <a href="{{ route('daily_records.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
        ➕ 新規記録追加
    </a>
</div>

{{-- フィルタリング機能 --}}
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('daily_records.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">年</label>
            <select name="year" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">全て</option>           
                @if(isset($categories))
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">月</label>
            <select name="month" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">全て</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ $m }}月
                    </option>
                @endfor
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">カテゴリ</label>
            <select name="category" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">全て</option>
        @if(isset($categories))
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        @endif
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg w-full">
                🔍 フィルタ
            </button>
        </div>
    </form>
</div>

{{-- 月別サマリー表示 --}}
@if(isset($monthlySummary) && $monthlySummary)
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">
        📊 {{ request('year', date('Y')) }}年{{ request('month') ? request('month').'月' : '' }}のサマリー
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="text-center">
            <p class="text-sm text-gray-600">収入合計</p>
            <p class="text-2xl font-bold text-green-600">¥{{ number_format($monthlySummary->total_income) }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">支出合計</p>
            <p class="text-2xl font-bold text-red-600">¥{{ number_format($monthlySummary->total_expense) }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">収支</p>
            <p class="text-2xl font-bold {{ $monthlySummary->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                ¥{{ number_format($monthlySummary->balance) }}
            </p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">記録件数</p>
            <p class="text-2xl font-bold text-blue-600">{{ $monthlySummary->record_count }}件</p>
        </div>
    </div>
</div>
@endif

{{-- 記録一覧テーブル --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日付</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">カテゴリ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">説明</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">金額</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($records as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $record->date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $record->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $record->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <span class="{{ $record->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $record->amount >= 0 ? '+' : '' }}¥{{ number_format($record->amount) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('daily_records.edit', $record) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">編集</a>
                                <form method="POST" action="{{ route('daily_records.destroy', $record) }}" 
                                      class="inline" onsubmit="return confirm('本当に削除しますか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">削除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            記録がありません
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ページネーション --}}
<div class="mt-6">
    {{ $records->withQueryString()->links() }}
</div>
@endsection
