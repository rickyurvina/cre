<?php

namespace App\Models\Risk;

use App\Abstracts\Model;
use App\Models\Common\CatalogDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Risk extends Model
{
    use HasFactory,LogsActivity;

    protected $table = 'risks';

    protected $fillable = ['name', 'description', 'cause', 'identification_date', 'closing_date',
        'incidence_date', 'cost', 'state_id', 'probability', 'impact', 'urgency',
        'classification', 'enabled', 'company_id','result_id','riskable_id', 'riskable_type'];

    const RISK_STATE_OPEN = "Abierto";
    const RISK_STATE_CLOSE = "Cerrado";

    const UPDATED = 'El riesgo fue actualizado';
    const CREATED =  'El riesgo fue creado';
    const DELETED = 'El riesgo fue eliominado';

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            if ($model->actions) {
                $model->actions->each->delete();
            }
        });
        static::creating(function ($model) {
            $model->name = mb_strtoupper($model->name);
            $model->description = mb_strtoupper($model->description);
            $model->cause = mb_strtoupper($model->cause);
        });
        static::updating(function ($model) {
            $model->name = mb_strtoupper($model->name);
            $model->description = mb_strtoupper($model->description);
            $model->cause = mb_strtoupper($model->cause);
        });
    }

    public function riskable(): MorphTo
    {
        return $this->morphTo();
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(CatalogDetail::class, 'state_id');
    }

    public function actions(){
        return $this->hasMany(RiskAction::class);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'updated':
                return Risk::UPDATED;
                break;
            case 'created':
                return Risk::CREATED;
                break;
            case 'deleted':
                return Risk::DELETED;
                break;
        }
    }
    public function isMemberOfTask()
    {
        $actions=$this->actions;
        $isMemeber=false;
        foreach ($actions as $action)
        {
            if($action->user->id==user()->id)
                 $isMemeber=true;
        }
        return $isMemeber;
    }
}
