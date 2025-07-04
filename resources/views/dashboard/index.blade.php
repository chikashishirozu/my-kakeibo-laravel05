{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'ダッシュボード - 家計簿アプリ')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- 現在の状況サマリー --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4">📊 現在の状況</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600">年間貯蓄目標</p>
                    <p class="text-2xl font-bold text-blue-600">
                        ¥{{ number_format(is_array($budgetGoal) ? ($budgetGoal['annual_savings_target'] ?? 0) : ($budgetGoal->annual_savings_target ?? 0)) }}
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">今月の収入</p>
                    <p class="text-2xl font-bold text-green-600">
                        ¥{{ number_format(is_array($currentMonthSummary) ? ($currentMonthSummary['total_income'] ?? 0) : ($currentMonthSummary->total_income ?? 0)) }}
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">今月の支出</p>
                    <p class="text-2xl font-bold text-red-600">
                        ¥{{ number_format(is_array($currentMonthSummary) ? ($currentMonthSummary['total_expense'] ?? 0) : ($currentMonthSummary->total_expense ?? 0)) }}
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">今月の収支</p>
                    @php
                        $balance = is_array($currentMonthSummary) ? ($currentMonthSummary['balance'] ?? 0) : ($currentMonthSummary->balance ?? 0);
                    @endphp
                    <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ¥{{ number_format($balance) }}
                    </p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">累計貯蓄額</p>
                        <p class="text-3xl font-bold text-purple-600">¥{{ number_format($totalSavings ?? 0) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">目標達成率</p>
                        <p class="text-2xl font-bold">{{ number_format($achievementRate ?? 0, 1) }}%</p>
                        <div class="w-32 bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($achievementRate ?? 0, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- 月別クイックアクセス --}}
    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4">📅 月別クイックアクセス</h3>
            <div class="grid grid-cols-3 gap-2">
                @for($month = 1; $month <= 12; $month++)
                    <a href="{{ route('daily_records.index', ['year' => date('Y'), 'month' => $month]) }}" 
                       class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-center py-2 px-3 rounded transition-colors {{ date('n') == $month ? 'ring-2 ring-blue-500' : '' }}">
                        {{ $month }}月
                    </a>
                @endfor
            </div>
        </div>
    </div>
</div>

<pre>{{ print_r($currentMonthSummary, true) }}</pre>
<pre>{{ print_r($monthlyData, true) }}</pre>

{{-- 月別収支グラフ --}}
<div class="mt-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">📈 月別収支推移</h3>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>
</div>

{{-- 使い方のヒント --}}
<div class="mt-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">💡 使い方のヒント</h3>
        <ul class="list-disc list-inside space-y-2 text-gray-700">
            <li>「新規記録」から毎日の収入・支出を記録しましょう</li>
            <li>収入は正の数、支出は負の数で入力します</li>
            <li>カテゴリを統一することで、より詳細な分析が可能になります</li>
            <li>月末に自動で集計・サマリーが作成されます</li>
            <li>「目標設定」で年間の貯蓄目標を設定し、進捗をモニタリングできます</li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 月別収支グラフの描画
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyData ?? []);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month + '月'),
            datasets: [
                {
                    label: '収入',
                    data: monthlyData.map(d => d.income || 0),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                },
                {
                    label: '支出',
                    data: monthlyData.map(d => Math.abs(d.expense || 0)),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                },
                {
                    label: '収支',
                    data: monthlyData.map(d => d.balance || 0),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '¥' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ¥' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
