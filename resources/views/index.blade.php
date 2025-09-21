@extends('activitylog-visualizer::layouts.app')

@section('title', 'Activity Log Visualizer')

@section('content')
<!-- Filters -->
<form method="GET">
    @include('activitylog-visualizer::components.filter-card', [
        'causers' => $causers,
        'events' => $events
    ])
</form>

<!-- Page Info and Export -->
@include('activitylog-visualizer::components.page-info', ['activities' => $activities])

<!-- Data Table -->
@include('activitylog-visualizer::components.activity-table', ['activities' => $activities])

<!-- Pagination -->
@if($activities->hasPages())
    @include('activitylog-visualizer::components.pagination', ['activities' => $activities])
@endif

<!-- Activity Modal -->
@include('activitylog-visualizer::components.activity-modal')

@endsection
