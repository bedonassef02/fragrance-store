<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdminMediaController extends Controller
{
    public function destroy(Media $media)
    {
        $media->delete();
        return back()->with('success', 'Image deleted successfully.');
    }
}
