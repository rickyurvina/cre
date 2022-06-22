<?php

namespace App\Models\Projects\Configuration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProjectThreshold extends Model
{
    protected $table = 'prj_thresholds';

    protected $fillable = [
        'progress_physic',
        'start_date',
        'end_date',
        'description',
        'thresholdable_id',
        'thresholdable_type',
        'properties',
    ];

    protected $casts = ['properties' => 'array'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->description = mb_strtoupper($model->description);
        });
        static::updating(function ($model) {
            $model->description = mb_strtoupper($model->description);
        });
    }

    public function thresholdable(): MorphTo
    {
        return $this->morphTo();
    }
}
