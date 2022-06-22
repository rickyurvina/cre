<?php

namespace App\Models\Poa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoaActivityPiatPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'poa_activity_piat_plan';

    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'id_poa_activity_piat',
        'task',
        'responsable',
        'date',
        'initial_time',
        'end_time',
    ];


    /**
     * PoaActivityPiatPlan poaActivities
     *
     * @return BelongsTo
     */
    public function poaActivityPiat(): BelongsTo
    {
        return $this->belongsTo(PoaActivityPiat::class, 'id_poa_activity_piat');
    }

    /**
     * PoaActivityPiatPlan responsable
     *
     * @return BelongsTo
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable');
    }

}
