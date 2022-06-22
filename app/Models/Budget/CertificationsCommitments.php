<?php

namespace App\Models\Budget;

use App\Abstracts\Model;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationsCommitments extends Model
{
    use HasFactory;

    public static function bootSoftDeletes()
    {
    }

    protected bool $tenantable = false;

    protected $table = 'bdg_certifications_commitments';

    protected $fillable = ['certification_id', 'commitment_id', 'year', 'bdg_account_id', 'amount'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * @param Money|float $value
     */
    protected function getAmountAttribute($value): Money
    {
        return money($value ?? 0);
    }
}
