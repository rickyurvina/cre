<?php

namespace App\Models\Projects\Activities;

use App\Abstracts\Model;
use App\Events\TaskDetailUpdated;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Plank\Mediable\Mediable;

class TaskDetails extends Model
{
    use HasFactory, Mediable;

    protected $table='prj_task_details';

    protected bool $tenantable=false;

    protected $casts=
        [
            'period'=>'datetime:Y-m'
        ]
    ;

    protected $fillable=
        [
            'prj_task_id',
            'goal',
            'progress',
            'period',
            'state',
            'company_id',
        ];

    public static function boot()
    {
        parent::boot();
        static::updated(function ($model) {
            TaskDetailUpdated::dispatch($model);
        });
    }

    public function task(){
        return $this->belongsTo(Task::class,'prj_task_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->withoutGlobalScope(\App\Scopes\Company::class);
    }


}
