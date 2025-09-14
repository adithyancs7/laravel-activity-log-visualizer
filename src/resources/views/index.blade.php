@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Activity Log Visualizer</h1>

        <canvas id="activityChart" style="max-width:700px;height:250px"></canvas>

        <table class="table">
            <thead>
                <tr>
                    <th>Log</th>
                    <th>Description</th>
                    <th>Causer</th>
                    <th>Subject</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $a)
                    <tr>
                        <td>{{ $a->log_name }}</td>
                        <td>{{ $a->description }}</td>
                        <td>{{ optional($a->causer)->name ?? class_basename($a->causer_type) }}</td>
                        <td>{{ class_basename($a->subject_type) }} #{{ $a->subject_id }}</td>
                        <td>{{ $a->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode(array_keys($countsPerDay)) !!};
        const data = {!! json_encode(array_values($countsPerDay)) !!};
        const ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Activities/day', data }] },
            options: {}
        });
    </script>
@endpush