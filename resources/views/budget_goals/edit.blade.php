{{-- resources/views/budget_goals/edit.blade.php --}}
@extends('layouts.app')

@section('title', '目標設定 - 家計簿アプリ')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">🎯 年間貯蓄目標設定</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('budget_goals.update', $budgetGoal->id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="annual_savings_target" class="block text-sm font-medium text-gray-700 mb-1">
                    年間貯蓄目標額
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">¥</span>
                    <input type="number" 
                           id="annual_savings_target" 
                           name="annual_savings_target" 
                           value="{{ old('annual_savings_target', $budgetGoal->annual_savings_target ?? 0) }}" 
                           class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="1000000"
                           min="0"
                           required>
                </div>
                <p class="text-sm text-gray-600 mt-1">
                    💡 今年一年間で貯蓄したい金額を入力してください
                </p>
                @error('annual_savings_target')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if(isset($budgetGoal) && $budgetGoal->annual_savings_target)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-blue-800 mb-2">現在の進捗状況</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-blue-600">目標額</p>
                        <p class="text-xl font-bold text-blue-800">
                            ¥{{ number_format($budgetGoal->annual_savings_target) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600">現在の貯蓄額</p>
                        <p class="text-xl font-bold text-green-600">
                            ¥{{ number_format($currentSavings ?? 0) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600">達成率</p>
                        <p class="text-xl font-bold">
                            {{ number_format($achievementRate ?? 0, 1) }}%
                        </p>
                    </div>
                </div>
                <div class="w-full bg-blue-200 rounded-full h-3 mt-4">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                         style="width: {{ min($achievementRate ?? 0, 100) }}%"></div>
                </div>
            </div>
            @endif

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-yellow-800 mb-2">💡 貯蓄目標のヒント</h3>
                <ul class="text-sm text-yellow-700 space-y-1">
                    <li>• 月収の10-20%を貯蓄目標にするのが一般的です</li>
                    <li>• 具体的な目標（旅行、車購入など）があると達成しやすくなります</li>
                    <li>• 無理のない範囲で設定し、達成できたら次の目標を立てましょう</li>
                    <li>• 月末に進捗をチェックして、必要に応じて支出を見直しましょう</li>
                </ul>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                    キャンセル
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    目標を保存
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
