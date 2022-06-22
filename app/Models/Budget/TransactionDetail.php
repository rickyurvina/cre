<?php

namespace App\Models\Budget;

use App\Abstracts\Model;
use App\Traits\CacheClear;
use Cknow\Money\Money;
use Cknow\Money\MoneyCast;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    protected $table = 'bdg_transaction_details';

    /**
     * Disable soft deletes for this model
     */
    public static function bootSoftDeletes() {}

    protected $fillable = ['number', 'debit', 'credit', 'description', 'transaction_id', 'account_id', 'company_id'];


    public static function boot()
    {
        parent::boot();
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * @param Money|float $value
     */
    protected function getDebitAttribute($value): Money
    {
        return money($value ?? 0);
    }

    /**
     * @param Money|float $value
     */
    protected function setDebitAttribute($value): void
    {
        $value = is_a($value, Money::class) ? $value : (is_null($value) ? $value : money($value));
        $this->attributes['debit'] = $value ? (int)$value->getAmount() : null;
    }

    /**
     * @param Money|float $value
     */
    protected function getCreditAttribute($value): Money
    {
        return money($value ?? 0);
    }

    /**
     * @param Money|float $value
     */
    protected function setCreditAttribute($value): void
    {
        $value = is_a($value, Money::class) ? $value : (is_null($value) ? $value : money($value));
        $this->attributes['credit'] = $value ? (int)$value->getAmount() : null;
    }
}
