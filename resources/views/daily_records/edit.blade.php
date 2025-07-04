{{-- resources/views/daily_records/edit.blade.php --}}
@extends('layouts.app')

@section('title', '記録編集 - 家計簿アプリ')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('daily_records.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
            ← 記録一覧に戻る
        </a>
        <h1 class="text-3xl font-bold">✏️ 記録編集</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('daily_records.update', $record) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">日付</label>
                    <input type="date" 
                           id="date" 
                           name="date" 
                           value="{{ old('date', $record->date->format('Y-m-d')) }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">カテゴリ</label>
                    <input type="text" 
                           id="category" 
                           name="category" 
                           value="{{ old('category', $record->category) }}" 
                           list="categories"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <datalist id="categories">
                        <option value="給与">
                        <option value="食費">
                        <option value="交通費">
                        <option value="光熱費">
                        <option value="家賃">
                        <option value="通信費">
                        <option value="娯楽費">
                        <option value="医療費">
                        <option value="その他収入">
                        <option value="その他支出">
                    </datalist>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">説明</label>
                <input type="text" 
                       id="description" 
                       name="description" 
                       value="{{ old('description', $record->description) }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">金額</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">¥</span>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           value="{{ old('amount', $record->amount) }}" 
                           class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">¥</span>
                    <input type="number" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                           id="annual_savings_target" 
                           name="annual_savings_target" 
                           value="{{ old('annual_savings_target', $budgetGoal->annual_savings_target ?? '') }}"
                           placeholder="例: 1000000" 
                           required>
                </div>                
                <p class="text-sm text-gray-600 mt-1">
                    💡 収入の場合は正の数、支出の場合は負の数で入力してください
                </p>
                @error('amount')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <div>
                
                    @if($budgetGoal->exists)
                        <div class="mb-4 p-3 bg-blue-50 rounded">
                            年間貯蓄目標: ¥{{ number_format($budgetGoal->annual_savings_target) }}
                        </div> 
                    @else
                        <p>budgetGoal変数が存在しません</p>
                    @endif                
                
                </div>
                
            </div>

            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('daily_records.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                    キャンセル
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
