<?php

namespace App\Models\Poa;

use App\Models\Auth\User;
use App\Models\Indicators\Indicator\Indicator;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class PoaIndicatorGoalChangeRequest extends Model
{
    use Mediable;

    const STATUS_OPEN = 'ABIERTO';
    const STATUS_APPROVED = 'APROBADO';
    const STATUS_DENIED = 'NEGADO';

    const STATUS_BG = [
        self::STATUS_OPEN => 'badge-info',
        self::STATUS_APPROVED => 'badge-success',
        self::STATUS_DENIED => 'badge-danger',
    ];

    protected $table = 'poa_indicator_goal_change_requests';

    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'poa_activity_indicator_id',
        'indicator_id',
        'period',
        'old_value',
        'new_value',
        'request_justification',
        'answer_justification',
        'request_user',
        'answer_user',
        'status',
        'request_number',
        'poa_activity_id',
    ];


    public function requestUser()
    {
        return $this->belongsTo(User::class, 'request_user');
    }
    public function activity(){
        return $this->hasMany(PoaActivity::class,'poa_activity_id');
    }

    public function poaActivity(){
        return $this->belongsTo(PoaActivityIndicator::class,'poa_activity_indicator_id');
    }

    public function answerUser()
    {
        return $this->belongsTo(User::class, 'answer_user');
    }

    public function indicator(){
        return $this->belongsTo(Indicator::class,'indicator_id');
    }
}
