<?php

namespace App\Models\Risk;

use App\Abstracts\Model;
use App\Events\RiskCreatedEvent;
use App\Models\Admin\Contact;
use App\Models\Auth\User;
use App\Models\Projects\Activities\Task;
use App\Models\Projects\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiskAction extends Model
{
    protected bool $tenantable = false;

    const OPEN='abierto';
    const CLOSED='cerrado';

    protected $fillable =
        [
            'name',
            'start_date',
            'end_date',
            'state',
            'color',
            'user_id',
            'risk_id',
            'task_id',
        ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->task){
                $model->task->parent = $model->task_id;
                $model->task->text = $model->name;
                $model->task->start_date = $model->start_date;
                $model->task->end_date = $model->end_date;
                $model->task->color = $model->color;
                $model->task->save();
            }
        });

        static::deleted(function ($model) {
            if ($model->task) {
                $model->task->delete();
            }
        });

        static::created(function ($model){
            if ($model->risk->riskable_type==Project::class){
                RiskCreatedEvent::dispatch($model);
            }
        });
        static::creating(function ($model) {
            $model->name = mb_strtoupper($model->name);
        });
        static::updating(function ($model) {
            $model->name = mb_strtoupper($model->name);
        });
    }

    public function risk()
    {
        return $this->belongsTo(Risk::class);
    }

    public function result()
    {
        return $this->belongsTo(Task::class,'task_id');
    }

    public function task()
    {
        return $this->morphOne(Task::class, 'taskable');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
