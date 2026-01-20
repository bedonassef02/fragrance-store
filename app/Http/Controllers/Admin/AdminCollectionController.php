<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Collection;
use App\Services\CollectionService;
use Illuminate\Http\Request;

class AdminCollectionController extends Controller
{
    protected $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = $this->collectionService->getAll(10);
        return view('admin.collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.collections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCollectionRequest $request)
    {
        $this->collectionService->create($request->validated());
        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCollectionRequest $request, Collection $collection)
    {
        $this->collectionService->update($collection, $request->validated());
        return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        $this->collectionService->delete($collection);
        return back()->with('success', 'Collection deleted successfully.');
    }
}
