@extends('admin.layout')

@section('title', admin_copy('admin.dashboard.title'))

@section('content')
@php
    $topChannels = [
        ['name' => 'Direct Store', 'category' => 'Commerce', 'revenue' => '$18,400', 'growth' => '+14.8%'],
        ['name' => 'Marketplace', 'category' => 'Partner', 'revenue' => '$12,980', 'growth' => '+9.2%'],
        ['name' => 'Social Shop', 'category' => 'Social', 'revenue' => '$10,440', 'growth' => '+6.4%'],
        ['name' => 'B2B Orders', 'category' => 'Wholesale', 'revenue' => '$8,730', 'growth' => '+4.1%'],
    ];

    $activities = [
        ['time' => '08:15', 'title' => 'Flash sale campaign launched', 'meta' => 'Marketing automation'],
        ['time' => '09:42', 'title' => 'Warehouse stock synced', 'meta' => 'Inventory service'],
        ['time' => '11:05', 'title' => '128 new orders processed', 'meta' => 'Order pipeline'],
        ['time' => '13:18', 'title' => 'RBAC audit completed', 'meta' => 'Security workspace'],
    ];

    $regionMetrics = [
        ['label' => 'Asia Pacific', 'value' => 72],
        ['label' => 'Europe', 'value' => 51],
        ['label' => 'North America', 'value' => 64],
    ];

    $revenueTrendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
    $revenueTrendSeries = [18, 22, 21, 28, 31, 36, 42];
    $orderTrendSeries = [11, 13, 16, 18, 19, 24, 27];
    $trafficLabels = [
        admin_copy('admin.dashboard.direct_channel'),
        admin_copy('admin.dashboard.social_channel'),
        admin_copy('admin.dashboard.marketplace_channel'),
        admin_copy('admin.dashboard.email_channel'),
    ];
    $trafficValues = [41, 27, 18, 14];
    $salesCategoryLabels = ['Workspace', 'Lighting', 'Storage', 'Decor', 'Furniture'];
    $salesCategoryValues = [96, 78, 65, 54, 109];
@endphp

<div class="space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight">{{ admin_copy('admin.dashboard.title') }}</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.dashboard.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300 px-4 py-2 font-semibold">
                <span class="size-2 rounded-full bg-emerald-500"></span>
                {{ admin_copy('admin.dashboard.live_data') }}
            </span>
            <span class="text-slate-500 dark:text-slate-400">{{ admin_copy('admin.dashboard.last_sync') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-primary/10 rounded-lg text-primary">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <span class="text-emerald-500 text-xs font-bold flex items-center bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full">+12.5%</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">{{ admin_copy('admin.dashboard.revenue') }}</p>
            <h3 class="text-2xl font-bold mt-1">$128,430.00</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-accent/10 rounded-lg text-accent">
                    <span class="material-symbols-outlined">person_celebrate</span>
                </div>
                <span class="text-rose-500 text-xs font-bold flex items-center bg-rose-50 dark:bg-rose-500/10 px-2 py-1 rounded-full">-2.4%</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">{{ admin_copy('admin.dashboard.users') }}</p>
            <h3 class="text-2xl font-bold mt-1">45,231</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-primary/10 rounded-lg text-primary">
                    <span class="material-symbols-outlined">local_mall</span>
                </div>
                <span class="text-emerald-500 text-xs font-bold flex items-center bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full">+8.1%</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">{{ admin_copy('admin.dashboard.orders') }}</p>
            <h3 class="text-2xl font-bold mt-1">12,543</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-accent/10 rounded-lg text-accent">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-emerald-500 text-xs font-bold flex items-center bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full">+0.5%</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">{{ admin_copy('admin.dashboard.conversion') }}</p>
            <h3 class="text-2xl font-bold mt-1">3.24%</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ admin_copy('admin.dashboard.performance_title') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.dashboard.performance_subtitle') }}</p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-primary/10 text-primary px-3 py-1 text-xs font-bold">
                    <span class="material-symbols-outlined text-base">show_chart</span>
                    +18.2%
                </span>
            </div>
            <div class="h-80">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </section>

        <section class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ admin_copy('admin.dashboard.traffic_title') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.dashboard.traffic_subtitle') }}</p>
            </div>
            <div class="h-72">
                <canvas id="trafficSourceChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-6 text-sm">
                <div class="rounded-lg bg-slate-50 dark:bg-slate-800/60 px-4 py-3">
                    <p class="text-slate-500 dark:text-slate-400">{{ admin_copy('admin.dashboard.direct_channel') }}</p>
                    <p class="font-bold text-slate-900 dark:text-slate-100 mt-1">41%</p>
                </div>
                <div class="rounded-lg bg-slate-50 dark:bg-slate-800/60 px-4 py-3">
                    <p class="text-slate-500 dark:text-slate-400">{{ admin_copy('admin.dashboard.social_channel') }}</p>
                    <p class="font-bold text-slate-900 dark:text-slate-100 mt-1">27%</p>
                </div>
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ admin_copy('admin.dashboard.sales_mix_title') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.dashboard.sales_mix_subtitle') }}</p>
                </div>
                <span class="text-xs font-semibold rounded-full px-3 py-1 bg-accent/10 text-accent">Q1 2026</span>
            </div>
            <div class="h-72">
                <canvas id="salesCategoryChart"></canvas>
            </div>
        </section>

        <section class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ admin_copy('admin.dashboard.pipeline_title') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.dashboard.pipeline_subtitle') }}</p>
            </div>
            <div class="space-y-5">
                @foreach ($regionMetrics as $region)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $region['label'] }}</span>
                            <span class="text-slate-500 dark:text-slate-400">{{ $region['value'] }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-primary to-accent" style="width: {{ $region['value'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 rounded-xl bg-slate-50 dark:bg-slate-800/60 p-4">
                <p class="text-xs uppercase tracking-[0.2em] font-bold text-slate-500 dark:text-slate-400">{{ admin_copy('admin.dashboard.goal_completion') }}</p>
                <div class="mt-3 flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900 dark:text-slate-100">84%</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.dashboard.goal_subtitle') }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-emerald-500 font-bold text-sm">
                        <span class="material-symbols-outlined text-base">north_east</span>
                        9.4%
                    </span>
                </div>
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <section class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ admin_copy('admin.dashboard.top_channels_title') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.dashboard.top_channels_subtitle') }}</p>
                </div>
                <span class="text-xs font-semibold rounded-full px-3 py-1 bg-primary/10 text-primary">{{ admin_copy('admin.dashboard.top_channels_badge') }}</span>
            </div>
            <div class="space-y-4">
                @foreach ($topChannels as $channel)
                    <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="size-11 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $channel['name'] }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $channel['category'] }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-bold text-slate-900 dark:text-slate-100">{{ $channel['revenue'] }}</p>
                            <p class="text-sm text-emerald-500">{{ $channel['growth'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ admin_copy('admin.dashboard.activity_title') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.dashboard.activity_subtitle') }}</p>
                </div>
                <span class="text-xs font-semibold rounded-full px-3 py-1 bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">{{ admin_copy('admin.dashboard.activity_badge') }}</span>
            </div>
            <div class="space-y-5">
                @foreach ($activities as $activity)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="size-3 rounded-full bg-primary mt-1"></div>
                            @if (! $loop->last)
                                <div class="w-px flex-1 bg-slate-200 dark:bg-slate-800 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $activity['title'] }}</p>
                                <span class="text-xs font-semibold text-slate-400">{{ $activity['time'] }}</span>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $activity['meta'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') return;

        const isDark = document.documentElement.classList.contains('dark');
        const labelColor = isDark ? '#cbd5e1' : '#64748b';
        const gridColor = isDark ? 'rgba(51, 65, 85, 0.45)' : 'rgba(203, 213, 225, 0.7)';

        Chart.defaults.color = labelColor;
        Chart.defaults.borderColor = gridColor;
        Chart.defaults.font.family = 'Space Grotesk, Public Sans, sans-serif';

        new Chart(document.getElementById('revenueTrendChart'), {
            type: 'line',
            data: {
                labels: {{ Illuminate\Support\Js::from($revenueTrendLabels) }},
                datasets: [
                    {
                        label: {{ Illuminate\Support\Js::from(admin_copy('admin.dashboard.revenue')) }},
                        data: {{ Illuminate\Support\Js::from($revenueTrendSeries) }},
                        borderColor: '#3471b7',
                        backgroundColor: 'rgba(52, 113, 183, 0.16)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: {{ Illuminate\Support\Js::from(admin_copy('admin.dashboard.orders')) }},
                        data: {{ Illuminate\Support\Js::from($orderTrendSeries) }},
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.12)',
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'start',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => '$' + value + 'k',
                        },
                    },
                },
            },
        });

        new Chart(document.getElementById('trafficSourceChart'), {
            type: 'doughnut',
            data: {
                labels: {{ Illuminate\Support\Js::from($trafficLabels) }},
                datasets: [{
                    data: {{ Illuminate\Support\Js::from($trafficValues) }},
                    backgroundColor: ['#3471b7', '#f97316', '#22c55e', '#a855f7'],
                    borderWidth: 0,
                }],
            },
            options: {
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
            },
        });

        new Chart(document.getElementById('salesCategoryChart'), {
            type: 'bar',
            data: {
                labels: {{ Illuminate\Support\Js::from($salesCategoryLabels) }},
                datasets: [{
                    label: {{ Illuminate\Support\Js::from(admin_copy('admin.dashboard.orders')) }},
                    data: {{ Illuminate\Support\Js::from($salesCategoryValues) }},
                    borderRadius: 10,
                    backgroundColor: ['#3471b7', '#4f86c6', '#78a8d8', '#99bee3', '#f97316'],
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    })();
</script>
@endsection
