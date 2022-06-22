<?php

namespace App\Models\Budget;


use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Budget\Structure\BudgetGeneralExpensesStructure;
use App\Models\Budget\Structure\BudgetStructure;
use App\Models\Comment;
use App\Models\Poa\PoaActivity;
use App\States\Transaction\Approved;
use App\States\Transaction\TransactionState;
use App\Traits\CacheClear;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Plank\Mediable\Mediable;
use Spatie\ModelStates\HasStates;

class Transaction extends Model
{
    use HasStates, Mediable;


    const TYPE_PROFORMA = 'PR';
    const TYPE_REFORM = 'RE';
    const TYPE_COMMITMENT = 'CO';
    const TYPE_CERTIFICATION = 'CE';
    const TYPE_ACCRUED = 'AS';

    const TYPE_BALANCE = [
        self::TYPE_PROFORMA,
        self::TYPE_ACCRUED,
        self::TYPE_COMMITMENT,
        self::TYPE_REFORM,
        self::TYPE_CERTIFICATION,
    ];
    const REFORM_TYPE_INCREMENT = 'Incremento';
    const REFORM_TYPE_DECREASE = 'Disminución';
    const REFORM_TYPE_TRANSFER = 'Transferencia';

    const REFORMS_TYPES =
        [
            self::REFORM_TYPE_INCREMENT,
            self::REFORM_TYPE_DECREASE,
            self::REFORM_TYPE_TRANSFER,
        ];

    const TYPES =
        [
            self::TYPE_PROFORMA => 'PROFORMA',
            self::TYPE_REFORM => 'REFORMA',
            self::TYPE_COMMITMENT => 'COMPROMISO',
            self::TYPE_CERTIFICATION => 'CERTIFICACIÓN',
            self::TYPE_COMMITMENT => 'COMPROMISO',
        ];

    const REFORMS_TYPES_BG = [
        self::REFORM_TYPE_INCREMENT => 'badge-primary',
        self::REFORM_TYPE_DECREASE => 'badge-secondary',
        self::REFORM_TYPE_TRANSFER => 'badge-success',
    ];

    protected $table = 'bdg_transactions';

    /**
     * Disable soft deletes for this model
     */
    public static function bootSoftDeletes()
    {
    }

    protected $fillable = [
        'year',
        'type',
        'number',
        'description',
        'created_by',
        'approved_by',
        'approved_date',
        'company_id',
        'reform_type'
    ];

    protected $appends = ['balance', 'debits', 'credits', 'totalBalance', 'balanceIncomes', 'balanceExpenses'];

    protected $casts = [
        'status' => TransactionState::class,
        'approved_date' => 'date'
    ];
    public static function boot()
    {
        parent::boot();
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionDetail::class)->withoutGlobalScopes();
    }

    public function structures()
    {
        return $this->hasMany(BudgetStructure::class, 'bdg_transaction_id');
    }

    public function getBalanceAttribute(): Money
    {
        if ($this->transactions()->count() > 0) {
            $balance = $this->transactions()->sum('debit') - $this->transactions()->sum('credit');
        } else {
            $balance = 0;
        }

        return money($balance);
    }

    public function getDebitsAttribute(): Money
    {
        if ($this->transactions()->count() > 0) {
            $debits = $this->transactions()->sum('debit');
        } else {
            $debits = 0;
        }

        return money($debits);
    }

    public function getCreditsAttribute(): Money
    {
        if ($this->transactions()->count() > 0) {
            $credits = $this->transactions()->sum('credit');
        } else {
            $credits = 0;
        }

        return money($credits);
    }

    public function debit($value, string $description = null, int $accountId = null): TransactionDetail
    {
        $value = is_a($value, Money::class)
            ? $value
            : money_parse_by_decimal($value, Money::getDefaultCurrency());
        return $this->post(null, $value, $description, $accountId);
    }

    public function credit($value, string $description = null, int $accountId = null): TransactionDetail
    {
        $value = is_a($value, Money::class)
            ? $value
            : money_parse_by_decimal($value, Money::getDefaultCurrency());
        return $this->post($value, null, $description, $accountId);
    }


    public function debitUpdate($value, string $description = null, int $transactionDetailId = null): TransactionDetail
    {
        $value = is_a($value, Money::class)
            ? $value
            : money_parse_by_decimal($value, Money::getDefaultCurrency());
        return $this->updateTransaction($transactionDetailId, null, $value, $description);
    }

    public function creditUpdate($value, string $description = null, int $transactionDetailId = null): TransactionDetail
    {
        $value = is_a($value, Money::class)
            ? $value
            : money_parse_by_decimal($value, Money::getDefaultCurrency());
        return $this->updateTransaction($transactionDetailId, $value, null, $description);
    }

    private function getNextNumber()
    {
        return $this->transactions()->max('number') + 1;
    }

    private function post(Money $credit = null, Money $debit = null, string $description = null, int $account_id = null): TransactionDetail
    {
        $transaction = new TransactionDetail;
        $transaction->number = $this->getNextNumber();
        $transaction->credit = $credit ? $credit->getAmount() : null;
        $transaction->debit = $debit ? $debit->getAmount() : null;
        $transaction->description = $description;
        $transaction->account_id = $account_id;
        $this->transactions()->save($transaction);
        return $transaction;
    }

    private function updateTransaction(int $transactionDetailId, Money $credit = null, Money $debit = null, string $description = null): TransactionDetail
    {
        $transaction = TransactionDetail::find($transactionDetailId);
        $transaction->credit = $credit ? $credit->getAmount() : null;
        $transaction->debit = $debit ? $debit->getAmount() : null;
        $transaction->description = $description;
        $this->transactions()->save($transaction);
        return $transaction;
    }

    public function approver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->withoutGlobalScope(\App\Scopes\Company::class);
    }

    public function getBalanceIncomesAttribute(): Money
    {
        $incomes = TransactionDetail::with(['account', 'transaction'])->whereHas('account', function ($query) {
            $query->where([
                ['type', Account::TYPE_INCOME],
                ['year', $this->year]
            ]);
        })->whereHas('transaction', function ($q) {
            $q->where('type', Transaction::TYPE_PROFORMA)->where('year', $this->year)
                ->whereState('status', Approved::class)->withoutGlobalScopes();
        });

        $total = 0;
        foreach ($incomes->get() as $income) {
            $total += $income->account->balance->getAmount();
        }
        $total = money($total);
        return $total;
    }

    public function getBalanceExpensesAttribute(): Money
    {
        $expenses = TransactionDetail::with(['account', 'transaction'])->whereHas('account', function ($query) {
            $query->where([
                ['type', Account::TYPE_EXPENSE],
                ['year', $this->year]
            ]);
        })->whereHas('transaction', function ($q) {
            $q->where('type', Transaction::TYPE_PROFORMA)->where('year', $this->year)
                ->whereState('status', Approved::class)->withoutGlobalScopes();
        });
        $total = 0;
        foreach ($expenses->get() as $expens) {
            $total += $expens->account->balance->getAmount();
        }
        $total = money($total);
        return $total;
    }

    public function getTotalBalanceAttribute(): Money
    {
        return money($this->balanceIncomes->getAmount() - $this->balanceExpenses->getAmount());
    }

    public function getBalanceIncomeDraftAttribute($status): Money
    {
        $expenses = TransactionDetail::with(['account', 'transaction'])->whereHas('account', function ($query) {
            $query->where([
                ['type', Account::TYPE_INCOME],
                ['year', $this->year]
            ]);
        });
        $total = 0;
        foreach ($expenses->get() as $expens) {
            $total += $expens->account->balanceDraft($status)->getAmount();
        }
        $total = money($total);
        return $total;
    }

    public function getBalanceExpenseDraftAttribute($status): Money
    {
        $expenses = TransactionDetail::with(['account', 'transaction'])->whereHas('account', function ($query) {
            $query->where([
                ['type', Account::TYPE_EXPENSE],
                ['year', $this->year]
            ]);
        });
        $total = 0;
        foreach ($expenses->get() as $expens) {
            $total += $expens->account->balanceDraft($status)->getAmount();
        }
        $total = money($total);
        return $total;
    }

    public function getBalanceIncome($status)
    {
        if ($this->status instanceof Approved) {
            return $this->getBalanceIncomesAttribute();
        } else {
            return $this->getBalanceIncomeDraftAttribute($status);
        }
    }

    public function getBalanceExpense($status)
    {
        if ($this->status instanceof Approved) {
            return $this->getBalanceExpensesAttribute();
        } else {
            return $this->getBalanceExpenseDraftAttribute($status);
        }
    }

    public function certifications(): BelongsToMany
    {
        return $this->belongsToMany(CertificationsCommitments::class, 'certification_id');
    }
    public function commitments(): BelongsToMany
    {
        return $this->belongsToMany(CertificationsCommitments::class, 'commitment_id');
    }

    public function budgetGeneralExpensesStructures(): HasMany
    {
        return $this->hasMany(BudgetGeneralExpensesStructure::class,'bdg_transaction_id');
    }

}
