<nav aria-label="Activity log pagination" class="mt-4">
    <div class="d-flex justify-content-center">
        <div class="pagination-tailwind">
            {{-- Previous --}}
            @if($activities->onFirstPage())
                <span class="page-btn disabled">Previous</span>
            @else
                <a href="{{ $activities->appends(request()->query())->previousPageUrl() }}" class="page-btn">Previous</a>
            @endif

            @php
                $start = max(1, $activities->currentPage() - 2);
                $end = min($activities->lastPage(), $activities->currentPage() + 2);
            @endphp

            @if($start > 1)
                <a href="{{ $activities->appends(request()->query())->url(1) }}" class="page-btn">1</a>
                @if($start > 2)<span class="page-dots">...</span>@endif
            @endif

            @for($page = $start; $page <= $end; $page++)
                @if($page == $activities->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $activities->appends(request()->query())->url($page) }}" class="page-btn">{{ $page }}</a>
                @endif
            @endfor

            @if($end < $activities->lastPage())
                @if($end < $activities->lastPage() - 1)<span class="page-dots">...</span>@endif
                <a href="{{ $activities->appends(request()->query())->url($activities->lastPage()) }}"
                    class="page-btn">{{ $activities->lastPage() }}</a>
            @endif

            {{-- Next --}}
            @if($activities->hasMorePages())
                <a href="{{ $activities->appends(request()->query())->nextPageUrl() }}" class="page-btn">Next</a>
            @else
                <span class="page-btn disabled">Next</span>
            @endif
        </div>
    </div>
</nav>