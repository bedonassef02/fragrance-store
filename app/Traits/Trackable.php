<?php

namespace App\Traits;

use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

trait Trackable
{
    public static function bootTrackable()
    {
        static::created(function ($model) {
            self::logAction($model, 'CREATED', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            // Only log if there are meaningful changes
            $changes = $model->getChanges();
            $original = [];
            
            // Filter out 'updated_at' if it's the only change
            if (count($changes) === 1 && isset($changes['updated_at'])) {
                return;
            }

            foreach ($changes as $key => $value) {
                $original[$key] = $model->getOriginal($key);
            }

            self::logAction($model, 'UPDATED', $original, $changes);
        });

        static::deleted(function ($model) {
            self::logAction($model, 'DELETED', $model->getAttributes(), null);
        });
    }

    protected static function logAction($model, $action, $original = null, $changes = null)
    {
        ActionLog::create([
            'user_id' => Auth::id(), // Will be null for guests/system
            'action' => $action,
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'original' => $original,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}
