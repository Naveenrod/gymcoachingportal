<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            static::logAction($model, 'created');
        });

        static::updated(function ($model) {
            if ($model->wasChanged()) {
                static::logAction($model, 'updated', [
                    'old' => array_intersect_key($model->getOriginal(), $model->getChanges()),
                    'new' => $model->getChanges(),
                ]);
            }
        });

        static::deleted(function ($model) {
            static::logAction($model, 'deleted');
        });
    }

    protected static function logAction($model, string $action, ?array $changes = null): void
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->getKey(),
                'changes' => $changes,
                'ip_address' => Request::ip(),
            ]);
        } catch (\Throwable $e) {
            // Don't let audit logging failures break the application
            report($e);
        }
    }
}
