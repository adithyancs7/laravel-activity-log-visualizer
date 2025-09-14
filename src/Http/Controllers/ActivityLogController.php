<?php

namespace Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
     public function index()
    {
        // simple: latest 50
        $activities = Activity::latest()->take(200)->get();

        // prepare counts per day for a small chart
        $countsPerDay = $activities->groupBy(fn($a) => $a->created_at->format('Y-m-d'))
                                   ->map->count()
                                   ->toArray();

        return view('activitylog-visualizer::index', compact('activities', 'countsPerDay'));
    }
}
