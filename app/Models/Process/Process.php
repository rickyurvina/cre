<?php

namespace App\Models\Process;

use App\Abstracts\Model;
use App\Models\Admin\Department;
use App\Models\Auth\User;
use App\Models\Comment;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Risk\Risk;
use App\States\Process\Act;
use App\States\Process\Check;
use App\States\Process\DoProcess;
use App\States\Process\Plan;
use App\States\Process\ProcessPhase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Plank\Mediable\Mediable;
use Spatie\ModelStates\HasStates;

class Process extends Model
{
    use HasFactory, HasStates, Mediable;

    const PHASES = [
        Plan::class,
        Act::class,
        DoProcess::class,
        Check::class,
    ];

    const PHASE_PLAN = 'Planear';
    const PHASE_DO_PROCESS = 'Hacer';
    const PHASE_ACT = 'Actuar';
    const PHASE_CHECK = 'Verificar';

    protected $casts = [
        'phase' => ProcessPhase::class
    ];

    protected $table = 'processes';

    protected $fillable =
        [
            'code',
            'name',
            'description',
            'owner_id',
            'company_id',
            'enabled',
            'phase',
            'department_id',
            'owner_id'
        ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->code = mb_strtoupper($model->code);
            $model->name = mb_strtoupper($model->name);
            $model->description = mb_strtoupper($model->description);
        });
        static::updating(function ($model) {
            $model->code = mb_strtoupper($model->code);
            $model->name = mb_strtoupper($model->name);
            $model->description = mb_strtoupper($model->description);
        });
    }

    public static function statusColor(string $status)
    {
        foreach (self::PHASES as $st) {
            if ($st::$name == $status) {
                return $st::color();
            }
        }
        return '';
    }

    public function phaseChanges(): \Illuminate\Support\Collection
    {
        $activities = $this->activities()
            ->where('description', '=', 'updated')
            ->orderBy('id')
            ->get();
        $activitiesCollection = new Collection();
        foreach ($activities as $activity) {
            $new = $activity->properties['attributes']['phase'];
            $old = $activity->properties['old']['phase'];
            if ($new != $old)
                $activitiesCollection->push($activity);
        }
        return collect($activitiesCollection);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->withoutGlobalScope(\App\Scopes\Company::class);
    }

    public function indicators(): MorphMany
    {
        return $this->morphMany(Indicator::class, 'indicatorable');
    }

    public function activitiesProcess()
    {
        return $this->hasMany(Activity::class, 'process_id')->orderBy('id', 'asc');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function risks()
    {
        return $this->morphMany(Risk::class, 'riskable');
    }

    public function nonConformities()
    {
        return $this->hasMany(NonConformities::class, 'process_id');
    }

}
