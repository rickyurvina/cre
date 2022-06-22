<?php

namespace App\Http\Controllers\Budget;

use App\Abstracts\Http\Controller;
use App\Jobs\Budgets\Incomes\BudgetIncomeDelete;
use App\Models\Budget\Account;
use App\Models\Budget\Structure\BudgetGeneralExpensesStructure;
use App\Models\Budget\Transaction;
use App\Models\Budget\TransactionDetail;
use App\Models\Poa\PoaActivity;
use App\Models\Projects\Activities\Task;
use App\States\Transaction\Approved;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\View\Factory;

class BudgetController extends Controller
{

    protected $view;

    public function __construct(\Illuminate\Contracts\View\Factory $view)
    {
        $this->view = $view;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $budgets = Transaction::query()->where('type', Transaction::TYPE_PROFORMA)->collect();
        return view('modules.budget.index', compact('budgets'));
    }

    public function reforms(Transaction $transaction)
    {
        return view('modules.budget.reforms.reforms', compact('transaction'));
    }


    public function show(Transaction $transaction)
    {
        $page = '';
        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.show', compact('transaction', 'page', 'incomesBalanceShow', 'expensesBalanceShow'));
    }

    public function incomes(Transaction $transaction)
    {
        $page = 'incomes';
        $incomes = Account::where([
                ['type', Account::TYPE_INCOME],
                ['year', $transaction->year],
            ]
        );
        $total = 0;
        foreach ($incomes->get() as $income) {
            $total += $income->balanceDraft($transaction->status)->getAmount();
        }
        $total = money($total);
        $incomes = $incomes->collect();
        $page = '';
        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.incomes.incomes', compact('transaction', 'page', 'incomes', 'total', 'incomesBalanceShow', 'expensesBalanceShow'));
    }

    public function expenses(Transaction $transaction)
    {
        $page = 'expenses';
        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.expenses.expenses', compact('transaction', 'page', 'incomesBalanceShow', 'expensesBalanceShow'));
    }

    public function expensesPoa(Transaction $transaction)
    {
        $page = 'expensesPoa';
        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.expenses.expenses-poa', compact('transaction', 'page', 'incomesBalanceShow', 'expensesBalanceShow'));
    }

    public function expensesProjects(Transaction $transaction)
    {
        $page = 'expensesProject';
        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.expenses.expenses-projects', compact('transaction', 'page', 'incomesBalanceShow', 'expensesBalanceShow'));
    }

    public function generalExpenses(Transaction $transaction)
    {
        $page = 'expensesProjectActivity';
        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.expenses.expenses-general', compact('page', 'incomesBalanceShow', 'expensesBalanceShow'))
            ->with('transaction', $transaction);
    }

    public function createBudgetGeneralExpenses(BudgetGeneralExpensesStructure $budgetGeneralExpensesStructure)
    {
        $budgetGeneralExpensesStructure->load(['transaction']);
        $transaction = $budgetGeneralExpensesStructure->transaction;
        $page = 'expensesProjectActivity';

        $expenses = Account::where([
                ['type', Account::TYPE_EXPENSE],
                ['accountable_id', $budgetGeneralExpensesStructure->id],
                ['accountable_type', BudgetGeneralExpensesStructure::class],
                ['year', $transaction->year],
            ]
        );

        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.expenses.create-expenses-general', compact('budgetGeneralExpensesStructure', 'expenses', 'page', 'incomesBalanceShow', 'expensesBalanceShow'))
            ->with('transaction', $transaction);
    }

    public function deleteIncome(int $id)
    {
        $income = Account::find($id);
        $transactionId = $income->transactionsDetails->first()->transaction->id;
        $data = [
            'id' => $id
        ];
        $response = $this->ajaxDispatch(new BudgetIncomeDelete($data));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 0, ['type' => __('budget.incomes')]))->success();
            return redirect()->route('budgets.incomes', $transactionId);

        } else {

            flash($response['message'])->error();
            return redirect()->route('budgets.incomes', $transactionId);
        }
    }

    public function expensesPoaActivity(Transaction $transaction, int $poaActivityId)
    {
        $activity = PoaActivity::find($poaActivityId);
        if ($activity->validateCrateBudget() === false) {
            abort(404);
        }
        $page = 'expensesPoaActivity';

        $expenses = Account::where([
                ['type', Account::TYPE_EXPENSE],
                ['accountable_id', $poaActivityId],
                ['accountable_type', PoaActivity::class],
                ['year', $transaction->year],
            ]
        );

        $total = 0;
        foreach ($expenses->get() as $expens) {
            $total += $expens->balanceDraft($transaction->status)->getAmount();
        }
        $total = money($total);
        $expenses = $expenses->collect();
        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.expenses.expenses-poa-activity', compact('activity', 'page', 'expenses', 'total', 'incomesBalanceShow', 'expensesBalanceShow'))
            ->with('transaction', $transaction);
    }

    public function expensesProjectActivity(Transaction $transaction, int $projectActivityId)
    {
        $activity = Task::find($projectActivityId);
        if ($activity->validateCrateBudget() === false) {
            abort(404);
        }

        $expenses = Account::where([
                ['type', Account::TYPE_EXPENSE],
                ['accountable_id', $projectActivityId],
                ['accountable_type', Task::class],
                ['year', $transaction->year],
            ]
        );

        $total = 0;

        foreach ($expenses->get() as $expens) {
            $total += $expens->balanceDraft($transaction->status)->getAmount();
        }

        $total = money($total);
        $expenses = $expenses->collect();
        $page = 'expensesProject';
        $incomesBalanceShow = $transaction->getBalanceIncome($transaction->status);
        $expensesBalanceShow = $transaction->getBalanceExpense($transaction->status);
        return view('modules.budget.expenses.expenses-project-activity', compact('activity', 'page', 'expenses', 'total', 'incomesBalanceShow', 'expensesBalanceShow'))
            ->with('transaction', $transaction);
    }

    public function deleteExpenseActivityProject(int $id, int $activity)
    {
        $expense = Account::find($id);
        $transactionId = $expense->transactionsDetails->first()->transaction->id;
        $data = [
            'id' => $id
        ];
        $response = $this->ajaxDispatch(new BudgetIncomeDelete($data));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 0, ['type' => __('budget.incomes')]))->success();
            return redirect()->route('budgets.expenses_project_activity', [$transactionId, $activity]);
        } else {
            flash($response['message'])->error();
            return redirect()->route('budgets.expenses_project_activity', [$transactionId, $activity]);
        }
    }

    public function deleteExpenseActivityPoa(int $id, int $activity)
    {
        $expense = Account::find($id);
        $transactionId = $expense->transactionsDetails->first()->transaction->id;
        $data = [
            'id' => $id
        ];
        $response = $this->ajaxDispatch(new BudgetIncomeDelete($data));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 0, ['type' => __('budget.incomes')]))->success();
            return redirect()->route('budgets.expenses_poa_activity', [$transactionId, $activity]);
        } else {
            flash($response['message'])->error();
            return redirect()->route('budgets.expenses_poa_activity', [$transactionId, $activity]);
        }
    }

    public function createReform(Transaction $transaction)
    {
        $number = Transaction::query()->where([
                ['year', '=', $transaction->year],
                ['type', '=', Transaction::TYPE_REFORM],
            ])->max('number') + 1;

        $newTransaction = Transaction::create([
            'year' => $transaction->year,
            'description' => '',
            'type' => Transaction::TYPE_REFORM,
            'number' => $number,
            'created_by' => user()->id,
            'company_id' => session('company_id'),
            'reform_type' => Transaction::REFORM_TYPE_INCREMENT,
        ]);
        return redirect()->route('budgets.viewCreatedReform', $newTransaction);
    }

    public function editReform(Transaction $transaction)
    {
        return view('modules.budget.reforms.edit-reform')->with('transaction', $transaction);
    }

    public function viewCreatedReform(Transaction $transaction)
    {
        return view('modules.budget.reforms.create-reform')->with('transaction', $transaction);
    }


    public function deleteExpenseGeneral(int $id)
    {
        $expense = Account::find($id);
        $budgetGeneralExpensesStructure=$expense->accountable;
        $transactionId = $expense->transactionsDetails->first()->transaction->id;
        $data = [
            'id' => $id
        ];
        $response = $this->ajaxDispatch(new BudgetIncomeDelete($data));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 0, ['type' => __('budget.incomes')]))->success();
            return redirect()->route('budgets.createBudgetGeneralExpenses', $budgetGeneralExpensesStructure);

        } else {
            flash($response['message'])->error();
            return redirect()->route('budgets.createBudgetGeneralExpenses', $budgetGeneralExpensesStructure);

        }
    }

    public function certifications(Transaction $transaction)
    {
        return view('modules.budget.certifications.certifications', compact('transaction'));
    }


    public function viewCreateCertification(Transaction $transaction)
    {
        return view('modules.budget.certifications.create-certification')->with('transaction', $transaction);
    }

    public function viewEditCertification(Transaction $transaction)
    {
        return view('modules.budget.certifications.edit-certifications')->with('transaction', $transaction);
    }

    public function commitments(Transaction $certification)
    {
        return view('modules.budget.commitments.commitments', compact('certification'));
    }

    public function viewCreateCommitment(Transaction $certification)
    {
        return view('modules.budget.commitments.create-commitment', ['transaction' => $certification]);
    }

    public function viewEditCommitment(Transaction $commitment, Transaction $certification)
    {
        return view('modules.budget.commitments.edit-commitment',
            [
                'certification' => $certification,
                'commitment' => $commitment,
            ]
        );
    }
}
