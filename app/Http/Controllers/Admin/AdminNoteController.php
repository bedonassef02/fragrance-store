<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminNoteController extends Controller
{
    public function index()
    {
        $notes = Note::withCount('products')->orderBy('name')->paginate(15);
        return view('admin.notes.index', compact('notes'));
    }

    public function create()
    {
        return view('admin.notes.create');
    }

    public function store(\App\Http\Requests\Admin\StoreNoteRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('notes', 'public');
        }

        Note::create($validated);

        return redirect()->route('admin.notes.index')->with('success', 'Note created successfully.');
    }

    public function edit(Note $note)
    {
        return view('admin.notes.edit', compact('note'));
    }

    public function update(\App\Http\Requests\Admin\UpdateNoteRequest $request, Note $note)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($note->image) {
                Storage::disk('public')->delete($note->image);
            }
            $validated['image'] = $request->file('image')->store('notes', 'public');
        }

        $note->update($validated);

        return redirect()->route('admin.notes.index')->with('success', 'Note updated successfully.');
    }

    public function destroy(Note $note)
    {
        if ($note->image) {
            Storage::disk('public')->delete($note->image);
        }

        // Pivot table entries will be deleted automatically due to constrained()->onDelete('cascade') in migration.
        $note->delete();

        return redirect()->route('admin.notes.index')->with('success', 'Note deleted successfully.');
    }
}
