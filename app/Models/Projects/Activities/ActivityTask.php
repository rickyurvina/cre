<?php

namespace App\Models\Projects\Activities;

use App\Abstracts\Model;
use App\Models\Auth\User;

class ActivityTask extends Model
{
    protected bool $tenantable = false;

    protected $fillable = [
        'code',
        'name',
        'prj_task_id',
        'status',
        'user_id',
    ];

    protected $table = 'prj_task_activities';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->name = mb_strtoupper($model->name);
            $model->code = mb_strtoupper($model->code);
        });
        static::updating(function ($model) {
            $model->name = mb_strtoupper($model->name);
            $model->code = mb_strtoupper($model->code);
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'prj_task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
