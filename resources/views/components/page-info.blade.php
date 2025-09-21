<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="small text-muted">
        @if($activities->total() > 0)
            Showing {{ $activities->firstItem() }} - {{ $activities->lastItem() }} of
            {{ number_format($activities->total()) }} results
        @else
            No results found
        @endif
    </div>
    <button type="button" class="btn btn-secondary btn-sm export-btn" {{ $activities->count() == 0 ? 'disabled' : '' }}>
        <span class="export-text"><i class="bi bi-download me-1"></i>Export</span>
    </button>
</div>