<?php

namespace App\Models\Budget;

use App\Abstracts\Model;
use App\States\Transaction\Approved;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Account extends Model
{

    const TYPE_INCOME = 1;
    const TYPE_EXPENSE = 2;

    const TYPES = [
        self::TYPE_INCOME => 'Ingreso',
        self::TYPE_EXPENSE => 'Gasto',
    ];

    protected $table = 'bdg_accounts';

    /**
     * Disable soft deletes for this model
     */
    public static function bootSoftDeletes()
    {
    }

    protected $casts = ['settings' => 'array'];

    protected $fillable =
        [
            'year',
            'type',
            'code',
            'name',
            'description',
            'parent_id',
            'company_id',
            'settings',
            'accountable_type',
            'accountable_id',
            'is_new',
        ];

    protected $appends = ['balance', 'balancePr', 'balanceRe', 'balanceCm', 'balanceAs'];

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $transaction = Transaction::where('year', $model->year)->where('type', Transaction::TYPE_PROFORMA)->first();
            if ($transaction->status instanceof Approved) {
                $model->is_new = true;
                $model->save();
            }
        });
    }

    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeIncomes(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_INCOME);
    }

    public function scopeExpenses(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_EXPENSE);
    }

    public function transactionsDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'account_id')->withoutGlobalScopes();
    }

    public function transactions()
    {
        return $this->hasMany(TransactionDetail::class, 'account_id')->withoutGlobalScopes()->whereHas('transaction', function ($q) {
            $q->whereIn('type', Transaction::TYPE_BALANCE)
                ->whereState('status', Approved::label())->withoutGlobalScopes();
        });
    }

    public function transactionsPr()
    {
        return $this->hasMany(TransactionDetail::class, 'account_id')
            ->withoutGlobalScopes()
            ->whereHas('transaction', function ($q) {
                $q->where('type', Transaction::TYPE_PROFORMA)
                    ->whereState('status', Approved::class)->withoutGlobalScopes();
            });
    }

    public function transactionsRe()
    {
        return $this->hasMany(TransactionDetail::class, 'account_id')->withoutGlobalScopes()->whereHas('transaction', function ($q) {
            $q->where('type', Transaction::TYPE_REFORM)
                ->whereState('status', Approved::class)->withoutGlobalScopes();
        });
    }

    public function transactionsAs()
    {
        return $this->hasMany(TransactionDetail::class, 'account_id')->withoutGlobalScopes()->whereHas('transaction', function ($q) {
            $q->where('type', Transaction::TYPE_ACCRUED)
                ->whereState('status', Approved::class)->withoutGlobalScopes();
        });
    }

    public function getBalancePrAttribute(): Money
    {
        $query = $this->transactionsPr();
        if ($query->count() > 0) {
            if ($this->type == self::TYPE_INCOME) {
                $balance = $query->sum('debit') - $query->sum('credit');
            } else {
                $balance = $query->sum('credit') - $query->sum('debit');
            }
        } else {
            $balance = 0;
        }
        return money($balance);
    }

    public function transactionsCm()
    {
        return $this->hasMany(TransactionDetail::class, 'account_id')->withoutGlobalScopes()->whereHas('transaction', function ($q) {
            $q->where('type', Transaction::TYPE_COMMITMENT)
                ->whereState('status', Approved::class)->withoutGlobalScopes();
        });
    }

    public function getBalanceCmAttribute(): Money
    {
        $query = $this->transactionsCm();
        if ($query->count() > 0) {
            if ($this->type == self::TYPE_INCOME) {
                $balance = $query->sum('debit') - $query->sum('credit');
            } else {
                $balance = $query->sum('credit') - $query->sum('debit');
            }
        } else {
            $balance = 0;
        }
        return money($balance);
    }

    public function getBalanceReAttribute(): Money
    {
        $query = $this->transactionsRe();
        if ($query->count() > 0) {
            if ($this->type == self::TYPE_INCOME) {
                $balance = $query->sum('debit') - $query->sum('credit');
            } else {
                $balance = $query->sum('credit') - $query->sum('debit');
            }
        } else {
            $balance = 0;
        }
        return money($balance);
    }

    public function getBalanceAsAttribute(): Money
    {
        $query = $this->transactionsAs();
        if ($query->count() > 0) {
            if ($this->type == self::TYPE_INCOME) {
                $balance = $query->sum('debit') - $query->sum('credit');
            } else {
                $balance = $query->sum('credit') - $query->sum('debit');
            }
        } else {
            $balance = 0;
        }
        return money($balance);
    }

    public function getBalanceAttribute(): Money
    {
        $query = $this->transactions();
        if ($query->count() > 0) {
            if ($this->type == self::TYPE_INCOME) {
                $balance = $query->sum('debit') - $query->sum('credit');
            } else {
                $balance = $query->sum('credit') - $query->sum('debit');
            }
        } else {
            $balance = 0;
        }
        return money($balance);
    }

    public function getBalanceEncoded()
    {
        return money($this->balance->getAmount() - $this->balanceRe->getAmount());
    }

    public function balanceDraft($state): Money
    {
        if ($state instanceof Approved) {
            return $this->getBalanceAttribute();
        } else {
            $query = $this->transactionsDetails();
            if ($query->count() > 0) {
                if ($this->type == self::TYPE_INCOME) {
                    $balance = $query->sum('debit') - $query->sum('credit');
                } else {
                    $balance = $query->sum('credit') - $query->sum('debit');
                }
            } else {
                $balance = 0;
            }
            return money($balance);
        }
    }

    public function balanceInitial(): Money
    {
        $query = $this->transactionsPr();
        if ($query->count() > 0) {
            if ($this->type == self::TYPE_INCOME) {
                $balance = $query->sum('debit') - $query->sum('credit');
            } else {
                $balance = $query->sum('credit') - $query->sum('debit');
            }
        } else {
            $balance = 0;
        }
        return money($balance);
    }

    //VALORES PARA COMPROMISOS
    public function transactionsCe($transactionId)
    {
        return $this->hasMany(TransactionDetail::class, 'account_id')
            ->withoutGlobalScopes()->whereHas('transaction', function ($q) use ($transactionId) {
                $q->where('id', $transactionId);
            });
    }

    public function getCertifiedValues($transactionId): Money
    {
        $query = $this->transactionsCe($transactionId);
        if ($query->count() > 0) {
            $balance = $query->sum('debit');
        } else {
            $balance = 0;
        }
        return money($balance);
    }
}
