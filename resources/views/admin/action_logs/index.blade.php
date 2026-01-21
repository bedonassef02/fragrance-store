@extends('layouts.admin')

@section('title', 'Activity Log')

@section('header', 'Activity Log')

@section('content')
    <div class="mb-6">
        <form action="{{ route('admin.action-logs.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="subject_type" class="block text-xs font-medium text-slate-400 mb-1">Subject Type</label>
                <select name="subject_type" id="subject_type" class="bg-slate-800 border border-slate-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">All Subjects</option>
                    @foreach($subjectTypes as $type)
                        <option value="{{ $type }}" {{ request('subject_type') == $type ? 'selected' : '' }}>
                            {{ class_basename($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="subject_id" class="block text-xs font-medium text-slate-400 mb-1">Subject ID</label>
                <input type="text" name="subject_id" id="subject_id" value="{{ request('subject_id') }}" placeholder="ID..." class="bg-slate-800 border border-slate-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-2.5">
            </div>

            <div>
                 <label for="action" class="block text-xs font-medium text-slate-400 mb-1">Action</label>
                 <select name="action" id="action" class="bg-slate-800 border border-slate-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">All Actions</option>
                    <option value="CREATED" {{ request('action') == 'CREATED' ? 'selected' : '' }}>Created</option>
                    <option value="UPDATED" {{ request('action') == 'UPDATED' ? 'selected' : '' }}>Updated</option>
                    <option value="DELETED" {{ request('action') == 'DELETED' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition-colors">
                Filter
            </button>
            
            @if(request()->anyFilled(['subject_type', 'subject_id', 'action']))
                <a href="{{ route('admin.action-logs.index') }}" class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg font-medium text-sm transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <x-admin.ui.glass-panel class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-900/50 uppercase tracking-wider text-xs font-bold text-slate-500">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Changes</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-300">
                                {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                            </div>
                            <div>
                                <div class="text-white font-medium">{{ $log->user->name ?? 'System' }}</div>
                                <div class="text-xs opacity-60">{{ $log->ip_address }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'CREATED' => 'emerald',
                                    'UPDATED' => 'blue',
                                    'DELETED' => 'rose',
                                ];
                            @endphp
                            <x-admin.ui.badge :type="$colors[$log->action] ?? 'slate'" :label="$log->action" />
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-200">{{ class_basename($log->subject_type) }}</div>
                            <div class="text-xs opacity-60">ID: {{ $log->subject_id }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            @if($log->action === 'UPDATED' && $log->changes)
                                <div class="space-y-1 text-xs">
                                    @foreach($log->changes as $key => $value)
                                        @if($key !== 'updated_at')
                                            <div class="flex items-start gap-1">
                                                <span class="font-mono text-slate-400">{{ $key }}:</span>
                                                <span class="text-rose-400 line-through">{{ Str::limit($log->original[$key] ?? 'null', 20) }}</span>
                                                <span class="text-emerald-400">&rarr; {{ Str::limit($value, 20) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif($log->action === 'CREATED')
                                <span class="text-xs text-slate-500">New Record Created</span>
                            @elseif($log->action === 'DELETED')
                                <span class="text-xs text-rose-500">Record Deleted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs whitespace-nowrap">
                            {{ $log->created_at->format('M d, Y') }}<br>
                            <span class="opacity-60">{{ $log->created_at->format('h:i A') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">No activity recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-700/50">
                {{ $logs->links() }}
            </div>
        @endif
    </x-admin.ui.glass-panel>
@endsection
