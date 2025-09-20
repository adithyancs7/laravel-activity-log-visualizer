<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log Visualizer</title>

    <!-- Instrument Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Package CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/activitylog-visualizer/css/bootstrap.min.css') }}">
    <script src="{{ asset('vendor/activitylog-visualizer/js/bootstrap.bundle.min.js') }}"></script>

    <style>
        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }

        .logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #dc3545, #b02a37);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo::before {
            content: '';
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 3px;
        }

        .filter-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            color: #6c757d;
            padding: 0.75rem 0.5rem;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .subject-separator {
            color: #6c757d;
        }

        .page-size-container {
            max-width: 120px;
        }

        .export-btn {
            min-width: 100px;
        }

        .export-btn:disabled {
            opacity: 0.6;
        }

        /* Custom pagination styling */
        .pagination .page-link {
            border: 1px solid #dee2e6;
            color: #495057;
            padding: 0.375rem 0.75rem;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .filter-card .row {
                gap: 0.25rem !important;
            }

            .filter-card .form-control-sm,
            .filter-card .form-select-sm {}

            .filter-card .form-label {}
        }

        @media (max-width: 992px) {
            .filter-card .row {
                flex-wrap: wrap;
            }

            .filter-card .col:not(.col-auto) {
                flex: 1 1 150px;
                min-width: 120px;
            }
        }

        @media (max-width: 768px) {
            .filter-row {
                gap: 0.5rem !important;
            }

            .table-responsive {}

            .filter-card .row {
                flex-direction: column;
            }

            .filter-card .col,
            .filter-card .col-auto {
                flex: none;
                width: 100%;
                max-width: none;
            }
        }

        .pagination-tailwind {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-tailwind .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.5rem;
            height: 1.5rem;
            padding: 0.6rem 0.5rem;
            font-weight: 500;
            color: #374151;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.3rem;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }

        .pagination-tailwind .page-btn:hover:not(.disabled) {
            background-color: #f3f4f6;
            border-color: #9ca3af;
            color: #1f2937;
        }

        .pagination-tailwind .page-btn.active {
            background-color: #c6c6c6;
            border-color: #c6c6c6;
            color: white;
        }

        .pagination-tailwind .page-btn.active:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .pagination-tailwind .page-btn.disabled {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }

        .pagination-tailwind .page-dots {
            color: #9ca3af;
            font-weight: 500;
            padding: 0.5rem 0.25rem;
        }
    </style>

</head>

<body>
    <!-- Header -->
    <div class="container-fluid bg-white border-bottom">
        <div class="container">
            <div class="d-flex align-items-center py-3">
                <div class="logo me-3"></div>
                <h1 class="h4 mb-0 fw-semibold">Activity Log Visualizer</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-4 fs-7">
        <!-- Filters -->
        <form method="GET" action="">
            <div class="filter-card fs-7">
                <div class="row g-2 align-items-end">
                    <!-- Search -->
                    <div class="col">
                        <label for="search" class="form-label small text-muted">Search</label>
                        <input type="search" name="search" id="search" class="form-control form-control-sm fs-7"
                            placeholder="Type key and hit enter" value="{{ request('search') }}">
                    </div>

                    <!-- Date Range -->
                    <div class="col">
                        <label for="start_date" class="form-label small text-muted">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm fs-7"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="col">
                        <label for="end_date" class="form-label small text-muted">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm fs-7"
                            value="{{ request('end_date') }}">
                    </div>

                    <!-- User Filter - Dynamic from causers collection -->
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

                    <!-- Event Filter - Dynamic from events collection -->
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

                    <!-- Clear Button -->
                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            onclick="window.location.href='{{ url()->current() }}'">
                            <i class="bi bi-arrow-clockwise"></i>
                            Clear Filters
                        </button>
                    </div>
                </div>


            </div>
        </form>

        <!-- Page Info and Export -->
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
                <span class="export-text">Export as CSV</span>
            </button>
        </div>

        <!-- Data Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 60px;">Sl No</th>
                            <th scope="col" style="width: 60px;">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Event</th>
                            <th scope="col">Causer</th>
                            <th scope="col">Properties</th>
                            <th scope="col">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $index => $activity)
                                                <tr class="activity-row" data-activity='{!! json_encode([
                                "id" => $activity->id,
                                "log_name" => $activity->log_name,
                                "description" => $activity->description,
                                "subject_id" => $activity->subject_id,
                                "subject_type" => $activity->subject_type,
                                "event" => $activity->event,
                                "causer" => $activity->causer ? $activity->causer->name ?? $activity->causer->email : null,
                                "causer_id"=>$activity->causer_id,
                                "causer_type"=>$activity->causer_type,
                                "properties" => $activity->properties,
                                "created_at" => $activity->created_at->format("Y-m-d H:i:s")
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}'>


                                                    <td>{{ $activities->firstItem() + $index }}</td>
                                                    <td>{{ $activity->id }}</td>
                                                    <td>{{ $activity->log_name }}</td>
                                                    <td>{{ $activity->description }}</td>
                                                    <td>
                                                        @if($activity->subject_type && $activity->subject_id)
                                                            {{ class_basename($activity->subject_type) }}
                                                            <span class="subject-separator">::</span>
                                                            {{ $activity->subject_id }}
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $badgeClass = match ($activity->event) {
                                                                'create' => 'bg-success',
                                                                'update' => 'bg-warning',
                                                                'delete' => 'bg-danger',
                                                                default => 'bg-primary'
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($activity->event) }}</span>
                                                    </td>
                                                    <td>
                                                        @if($activity->causer)
                                                            {{ $activity->causer->name ?? $activity->causer->email ?? 'User #' . $activity->causer_id }}
                                                        @else
                                                            <span class="text-muted">System</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($activity->properties->isNotEmpty())
                                                            <small
                                                                class="text-muted font-monospace">{{ (strlen($activity->properties) > 20 ? substr($activity->properties, 0, 20) . '...' : $activity->properties) }}</small>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $activity->created_at->format('Y-m-d H:i:s') }}</small>
                                                    </td>
                                                </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                    No activity logs found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
            <nav aria-label="Activity log pagination" class="mt-4">
                <div class="d-flex justify-content-center">
                    <div class="pagination-tailwind">
                        {{-- Previous Button --}}
                        @if($activities->onFirstPage())
                            <span class="page-btn disabled">
                                Previous
                            </span>
                        @else
                            <a href="{{ $activities->appends(request()->query())->previousPageUrl() }}" class="page-btn">
                                Previous
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $start = max(1, $activities->currentPage() - 2);
                            $end = min($activities->lastPage(), $activities->currentPage() + 2);
                        @endphp

                        {{-- First Page --}}
                        @if($start > 1)
                            <a href="{{ $activities->appends(request()->query())->url(1) }}" class="page-btn">1</a>
                            @if($start > 2)
                                <span class="page-dots">...</span>
                            @endif
                        @endif

                        {{-- Page Range --}}
                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $activities->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $activities->appends(request()->query())->url($page) }}"
                                    class="page-btn">{{ $page }}</a>
                            @endif
                        @endfor

                        {{-- Last Page --}}
                        @if($end < $activities->lastPage())
                            @if($end < $activities->lastPage() - 1)
                                <span class="page-dots">...</span>
                            @endif
                            <a href="{{ $activities->appends(request()->query())->url($activities->lastPage()) }}"
                                class="page-btn">{{ $activities->lastPage() }}</a>
                        @endif

                        {{-- Next Button --}}
                        @if($activities->hasMorePages())
                            <a href="{{ $activities->appends(request()->query())->nextPageUrl() }}" class="page-btn">
                                Next
                            </a>
                        @else
                            <span class="page-btn disabled">
                                Next
                            </span>
                        @endif
                    </div>
                </div>
            </nav>
        @endif
    </div>

    <div class="modal fade" id="activityDetailsModal" tabindex="-1" aria-labelledby="activityDetailsLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activityDetailsLabel">Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm mb-3">
                        <tbody id="activity-details-table" class="fs-7"></tbody>
                    </table>
                    <h6 class="fw-semibold">Properties</h6>
                    <pre class="bg-light p-2 rounded border" id="activity-properties"
                        style="max-height: 300px; overflow:auto;"></pre>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, select');

            // Auto-submit form on filter change
            inputs.forEach(el => {
                el.addEventListener('change', () => {
                    // Don't auto-submit on search input - let user finish typing
                    if (el.name === 'search') return;
                    form.submit();
                });
            });

            // Submit search on Enter key
            const searchInput = document.getElementById('search');
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    form.submit();
                }
            });

            // Export functionality
            const exportBtn = document.querySelector('.export-btn');

            if (exportBtn && !exportBtn.disabled) {
                exportBtn.addEventListener('click', async () => {
                    try {
                        exportBtn.disabled = true;
                        exportBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Exporting...';

                        // Collect current filters as query parameters
                        const params = new URLSearchParams(new FormData(form));
                        params.append('export', 'csv'); // Add export parameter

                        // Make request to export endpoint
                        const response = await fetch(`{{ url('/activity-log-visualizer/export') }}?${params.toString()}`);

                        if (!response.ok) {
                            throw new Error('Export failed');
                        }

                        // Create download link
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `activity-log-export-${new Date().toISOString().split('T')[0]}.csv`;
                        document.body.appendChild(a);
                        a.click();

                        // Cleanup
                        a.remove();
                        window.URL.revokeObjectURL(url);

                    } catch (err) {
                        // Show Bootstrap alert
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            <strong>Error!</strong> ${err.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.container').prepend(alertDiv);
                    } finally {
                        exportBtn.disabled = false;
                        exportBtn.innerHTML = '<i class="bi bi-download me-1"></i><span class="export-text">Export</span>';
                    }
                });
            }

            // Initialize Bootstrap tooltips if needed
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            const rows = document.querySelectorAll('.activity-row');
            const modal = new bootstrap.Modal(document.getElementById('activityDetailsModal'));
            const detailsTable = document.getElementById('activity-details-table');
            const propertiesPre = document.getElementById('activity-properties');

            rows.forEach(row => {
                row.addEventListener('click', () => {
                    try {
                        const activity = JSON.parse(row.dataset.activity);

                        // Build table rows dynamically
                        detailsTable.innerHTML = `
                            <tr class="table-primary">
                                <th scope="row" style="width: 150px;">ID</th>
                                <td class="fw-semibold">${activity.id}</td>
                            </tr>
                            <tr>
                                <th scope="row">Log Name</th>
                                <td>${activity.log_name}</td>
                            </tr>
                            <tr>
                                <th scope="row">Description</th>
                                <td class="text-muted">${activity.description ?? '—'}</td>
                            </tr>
                            <tr>
                                <th scope="row">Subject ID</th>
                                <td class="fw-semibold">${activity.subject_id ?? '—'}</td>
                            </tr>
                            <tr class="table-light">
                                <th scope="row">Subject Type</th>
                                <td>${activity.subject_type ?? '—'}</td>
                            </tr>
                            <tr>
                                <th scope="row">Event</th>
                                <td>
                                    <span class="text-capitalize">${activity.event}</span>
                                </td>
                            </tr>
                            <tr class="table-light">
                                <th scope="row">Causer</th>
                                <td>${activity.causer}</td>
                            </tr>
                            <tr>
                                <th scope="row">Causer ID</th>
                                <td class="fw-semibold">${activity.causer_id ?? '—'}</td>
                            </tr>
                            <tr class="table-light">
                                <th scope="row">Causer Type</th>
                                <td>${activity.causer_type ?? '—'}</td>
                            </tr>
                            <tr>
                                <th scope="row">Timestamp</th>
                                <td class="text-muted">${activity.created_at}</td>
                            </tr>
                        `;


                        // Pretty-print properties
                        propertiesPre.textContent = JSON.stringify(activity.properties, null, 2);

                        modal.show();
                    } catch (err) {
                        console.error("Failed to load activity details", err);
                    }
                });
            });

        });
    </script>
</body>

</html>