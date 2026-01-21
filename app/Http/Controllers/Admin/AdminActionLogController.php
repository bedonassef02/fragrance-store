<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActionLog;
use Illuminate\Http\Request;

class AdminActionLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActionLog::with('user');

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        $subjectTypes = ActionLog::select('subject_type')->distinct()->pluck('subject_type');

        return view('admin.action_logs.index', compact('logs', 'subjectTypes'));
    }
}
