<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Subject</th>
                    <th>Event</th>
                    <th>Causer</th>
                    <th>Properties</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $index => $activity)
                    <tr class="activity-row" data-activity='@json($activity)'>
                        <td>{{ $activities->firstItem() + $index }}</td>
                        <td>{{ $activity->id }}</td>
                        <td>{{ $activity->log_name }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ $activity->subject_type ? class_basename($activity->subject_type) . '::' . $activity->subject_id : '—' }}
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
                        <td>{{ $activity->causer->name ?? $activity->causer->email ?? 'System' }}</td>
                        <td>{{ $activity->properties->isNotEmpty() ? Str::limit($activity->properties, 20) : '—' }}</td>
                        <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No activity logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>