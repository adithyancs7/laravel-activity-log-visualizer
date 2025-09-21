<div class="filter-card fs-7">
    <div class="row g-2 align-items-end">
        <!-- Search -->
        <div class="col">
            <label for="search" class="form-label small text-muted">Search</label>
            <input type="search" name="search" id="search" class="form-control form-control-sm fs-7"
                   placeholder="Type key and hit enter" value="{{ request('search') }}">
        </div>

        <!-- Start Date -->
        <div class="col">
            <label for="start_date" class="form-label small text-muted">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control form-control-sm fs-7"
                   value="{{ request('start_date') }}">
        </div>

        <!-- End Date -->
        <div class="col">
            <label for="end_date" class="form-label small text-muted">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control form-control-sm fs-7"
                   value="{{ request('end_date') }}">
        </div>

        <!-- User -->
        <div class="col">
            <label for="causer_id" class="form-label small text-muted">User</label>
            <select name="causer_id" id="causer_id" class="form-select form-select-sm fs-7">
                <option value="">All Users</option>
                @foreach($causers as $causer)
                    <option value="{{ $causer->id }}" {{ request('causer_id') == $causer->id ? 'selected' : '' }}>
                        {{ $causer->name ?? $causer->email ?? 'User #' . $causer->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Event -->
        <div class="col">
            <label for="event" class="form-label small text-muted">Event</label>
            <select name="event" id="event" class="form-select form-select-sm fs-7">
                <option value="">All Events</option>
                @foreach($events as $eventType)
                    <option value="{{ $eventType }}" {{ request('event') == $eventType ? 'selected' : '' }}>
                        {{ ucfirst($eventType) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Page Size -->
        <div class="col-auto" style="min-width: 100px;">
            <label for="page_size" class="form-label small text-muted">Page Size</label>
            <input type="number" name="paginate" id="page_size" class="form-control form-control-sm fs-7"
                   value="{{ request('paginate', 100) }}" max="100" min="10">
        </div>

        <!-- Clear -->
        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary btn-sm"
                    onclick="window.location.href='{{ url()->current() }}'">
                <i class="bi bi-arrow-clockwise"></i> Clear Filters
            </button>
        </div>
    </div>
</div>
