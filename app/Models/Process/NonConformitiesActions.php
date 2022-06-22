<?php

namespace App\Models\Process;

use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Plank\Mediable\Mediable;

class NonConformitiesActions extends Model
{
    use Mediable;

    protected $table = 'processes_non_conformities_actions';

    protected bool $tenantable = false;

    const STATUS_IN_PROCESS='EN PROCESO';
    const STATUS_COMPLETED='CUMPLIDA';
    const STATUS_ON_DELAY='CON RESTRASO';
    const STATUS_COMPLETED_ON_DELAY='CUMPLIDA CON RETRASO';
    const STATUS_FROZEN='CONGELADA';
    const STATUS_DELETED='ELIMINADA';

    const STATUES=
        [
            self::STATUS_IN_PROCESS=>self::STATUS_IN_PROCESS,
            self::STATUS_COMPLETED=>self::STATUS_COMPLETED,
            self::STATUS_ON_DELAY=>self::STATUS_ON_DELAY,
            self::STATUS_COMPLETED_ON_DELAY=>self::STATUS_COMPLETED_ON_DELAY,
            self::STATUS_FROZEN=>self::STATUS_FROZEN,
            self::STATUS_DELETED=>self::STATUS_DELETED,
        ];
    const STATUES_NO_COMPLETED=
        [
            self::STATUS_IN_PROCESS=>self::STATUS_IN_PROCESS,
            self::STATUS_ON_DELAY=>self::STATUS_ON_DELAY,
            self::STATUS_FROZEN=>self::STATUS_FROZEN,
            self::STATUS_DELETED=>self::STATUS_DELETED,
        ];

    protected $fillable = [
        'name',
        'implantation_date',
        'status',
        'start_date',
        'end_date',
        'processes_non_conformities_id',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->status = mb_strtoupper($model->status);
            $model->name = mb_strtoupper($model->name);
        });
        static::updating(function ($model) {
            $model->status = mb_strtoupper($model->status);
            $model->name = mb_strtoupper($model->name);
        });
    }

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'implantation_date' => 'date:Y-m-d',
    ];

    public function nonConformity()
    {
        return $this->belongsTo(NonConformities::class, 'processes_non_conformities_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->withoutGlobalScope(\App\Scopes\Company::class);
    }
}
