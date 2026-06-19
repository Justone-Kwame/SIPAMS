<?php

namespace App\Services;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log(string $activity, string $description = null, $model = null)
    {
        $data = [
            'user_id' => Auth::id(),
            'activity' => $activity,
            'description' => $description,
            'ip_address' => Request::ip()
        ];

        if ($model) {
            $data['model_type'] = get_class($model);
            $data['model_id'] = $model->id;
        }

        return Audit::create($data);
    }
}
