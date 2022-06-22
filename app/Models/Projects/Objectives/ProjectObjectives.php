<?php

namespace App\Models\Projects\Objectives;


use App\Abstracts\Model;
use App\Events\ProjectColorUpdated;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Projects\Catalogs\ProjectLineActionService;
use App\Models\Projects\Project;
use App\Models\Projects\Activities\Task;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProjectObjectives extends Model
{
    protected $table = 'prj_project_objectives';

    protected bool $tenantable = false;


    protected $fillable = ['code', 'name', 'description', 'prj_project_id', 'color'];

    public static function boot()
    {
        parent::boot();
        static::updated(function ($model) {
            event(new ProjectColorUpdated($model));
        });
        static::creating(function ($model) {
            $model->name = mb_strtoupper($model->name);
            $model->code = mb_strtoupper($model->code);
            $model->description = mb_strtoupper($model->description);
        });
        static::updating(function ($model) {
            $model->name = mb_strtoupper($model->name);
            $model->code = mb_strtoupper($model->code);
            $model->description = mb_strtoupper($model->description);
        });
    }


    public function project()
    {
        return $this->belongsTo(Project::class, 'prj_project_id');
    }

    public function indicators(): MorphMany
    {
        return $this->morphMany(Indicator::class, 'indicatorable');
    }

    public function results()
    {
        return $this->hasMany(Task::class, 'objective_id');
    }
}
