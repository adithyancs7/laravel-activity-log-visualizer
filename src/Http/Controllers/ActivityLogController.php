<?php

namespace Adithyan\ActivityLogVisualizer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->latest()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('causer_id'), function ($q) use ($request) {
                $q->where('causer_id', $request->causer_id);
            })
            ->when($request->filled('event'), function ($q) use ($request) {
                // normalize case (DB usually stores lowercase events)
                $q->whereRaw('LOWER(event) = ?', [strtolower($request->event)]);
            })
            ->when($request->filled('subject_type'), function ($q) use ($request) {
                $q->where('subject_type', $request->subject_type);
            })
            ->when($request->filled('start_date') || $request->filled('end_date'), function ($q) use ($request) {
                $start = $request->start_date
                    ? $request->start_date . ' 00:00:00'
                    : '1970-01-01 00:00:00'; // fallback to very old date
    
                $end = $request->end_date
                    ? $request->end_date . ' 23:59:59'
                    : now(); // fallback to current time
    
                $q->whereBetween('created_at', [$start, $end]);
            });

        // Paginate results with max limit 100
        $activities = $query->paginate(
            min((int) $request->query('paginate', 100), 100)
        )->appends($request->query());

        // Get filter options for dropdowns
        $causers = $this->getUniqueCausers();
        $events = $this->getUniqueEvents();

        return view('activitylog-visualizer::index', compact('activities', 'causers', 'events'));
    }


    /**
     * Get unique causers for filter dropdown
     */
    protected function getUniqueCausers()
    {
        return Activity::whereNotNull('causer_id')
            ->with('causer')
            ->get()
            ->pluck('causer')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    /**
     * Get unique events for filter dropdown
     */
    protected function getUniqueEvents()
    {
        return Activity::select('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event')
            ->filter()
            ->values();
    }

    /**
     * Get unique subject types for potential future filtering
     */
    protected function getUniqueSubjectTypes()
    {
        return Activity::select('subject_type')
            ->distinct()
            ->whereNotNull('subject_type')
            ->orderBy('subject_type')
            ->pluck('subject_type')
            ->map(function ($subjectType) {
                return [
                    'full' => $subjectType,
                    'short' => class_basename($subjectType)
                ];
            })
            ->values();
    }

    /**
     * Export activities (optional feature)
     */
    public function export(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('causer_id'), function ($q) use ($request) {
                $q->where('causer_id', $request->causer_id);
            })
            ->when($request->filled('event'), function ($q) use ($request) {
                // normalize case (DB usually stores lowercase events)
                $q->whereRaw('LOWER(event) = ?', [strtolower($request->event)]);
            })
            ->when($request->filled('subject_type'), function ($q) use ($request) {
                $q->where('subject_type', $request->subject_type);
            })
            ->when($request->filled('start_date') || $request->filled('end_date'), function ($q) use ($request) {
                $start = $request->start_date
                    ? $request->start_date . ' 00:00:00'
                    : '1970-01-01 00:00:00'; // fallback to very old date
    
                $end = $request->end_date
                    ? $request->end_date . ' 23:59:59'
                    : now(); // fallback to current time
    
                $q->whereBetween('created_at', [$start, $end]);
            })
            ->latest();

        $activities = $query->get();

        $filename = 'activity_log_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($activities) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Description',
                'Event',
                'Causer ID',
                'Causer Type',
                'Causer',
                'Subject Type',
                'Subject ID',
                'Properties',
                'Batch UUID',
                'Created At'
            ]);

            // Data rows
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->description,
                    $activity->event,
                    $activity->causer_id,
                    $activity->causer_type,
                    $activity->causer ? ($activity->causer->name ?? $activity->causer->email) : 'System',
                    $activity->subject_type,
                    $activity->subject_id,
                    json_encode($activity->properties),
                    $activity->batch_uuid,
                    $activity->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show single activity details (optional)
     */
    public function show($id)
    {
        $activity = Activity::with(['causer', 'subject'])->findOrFail($id);

        return view('activitylog-visualizer::show', compact('activity'));
    }

    /**
     * Bulk delete activities (admin feature)
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_log,id'
        ]);

        Activity::whereIn('id', $request->ids)->delete();

        return redirect()->route('activitylog-visualizer.index')
            ->with('success', 'Selected activities have been deleted.');
    }

    /**
     * Clear old activities
     */
    public function clearOld(Request $request)
    {
        $days = $request->input('days', 30);

        $deleted = Activity::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('activitylog-visualizer.index')
            ->with('success', "Deleted {$deleted} activities older than {$days} days.");
    }
}