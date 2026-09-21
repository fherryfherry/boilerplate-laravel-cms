@php
    $currentSort = request('sort', $defaultSort ?? null);
    $currentDirection = request('direction', $defaultDirection ?? 'asc');
    $isActive = $currentSort === $sort;
    $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
    $query = array_merge(request()->query(), ['sort' => $sort, 'direction' => $nextDirection]);
    unset($query['page']);

    $icon = 'unfold_more';
    if ($isActive) {
        $icon = $currentDirection === 'asc' ? 'arrow_upward' : 'arrow_downward';
    }
@endphp

<th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider {{ $align ?? '' }}">
    <a href="{{ url()->current() . '?' . http_build_query($query) }}" class="inline-flex items-center gap-1.5 hover:text-primary transition-colors">
        <span>{{ $label }}</span>
        <span class="material-symbols-outlined text-sm {{ $isActive ? 'text-primary' : 'text-slate-400' }}">{{ $icon }}</span>
    </a>
</th>
