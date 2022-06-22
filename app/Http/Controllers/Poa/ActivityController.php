<?php

namespace App\Http\Controllers\Poa;

use Akaunting\Setting\Support\Arr;
use App\Abstracts\Http\Controller;
use App\Jobs\Poa\DeletePoaActivityPiat;
use App\Models\Budget\Account;
use App\Models\Budget\TransactionDetail;
use App\Models\Common\CatalogGeographicClassifier;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityPiat;
use App\Models\Poa\PoaIndicatorGoalChangeRequest;
use App\States\Poa\InProgress;
use GrahamCampbell\ResultType\Success;

class ActivityController extends Controller
{
    public $rule;


    public function index(Poa $poa)
    {
        return view('modules.poa.activity.index', compact('poa'));
    }

    public function edit(PoaActivity $activity)
    {
        if (!$activity->program->poa->status instanceof InProgress) {
            abort(404);
        }

        $activity->load([
            'poaActivityIndicator.goalIndicator',
            'indicator.indicatorUnit',
            'planDetail',
            'indicator',
            'program.poa',
            'location',
            'accounts.transactionsDetails'
        ]);

        $expenses = $activity->accounts->pluck('transactionsDetails')->collapse();

        $credits = $expenses->pluck('credit');
        $total = 0;
        foreach ($credits as $credit) {
            $total += $credit->getAmount();
        }
        $rule='required|max:255';

        $poaCurrentPhase = $activity->program->poa->phase;

        return view('modules.poa.activity.update', compact('activity', 'expenses', 'total','rule', 'poaCurrentPhase'));
    }

    public function show(PoaActivity $activity)
    {
        $activity->load([
            'poaActivityIndicator.goalIndicator',
            'indicator.indicatorUnit',
            'planDetail',
            'indicator',
            'program.poa',
            'location'
        ]);
        $listRequests = PoaIndicatorGoalChangeRequest::whereIn('poa_activity_indicator_id', $activity->poaActivityIndicator->pluck('id'))->get()->groupBy('request_number');
        $data = [];
        $contApproved = 0;
        $contDeclined = 0;
        $contOpen = 0;
        foreach ($listRequests as $item) {
            switch ($item->first()->status) {
                case PoaIndicatorGoalChangeRequest::STATUS_APPROVED:
                    $contApproved++;
                    break;
                case PoaIndicatorGoalChangeRequest::STATUS_OPEN:
                    $contOpen++;
                    break;
                case PoaIndicatorGoalChangeRequest::STATUS_DENIED:
                    $contDeclined++;
                    break;
            }
        }
        $data[] = [
            'abiertas' => $contOpen,
            'aprobadas' => $contApproved,
            'rechazadas' => $contDeclined,
        ];
        return view('modules.poa.activity.show', compact('activity', 'data'));
    }

    public function deleteMatrixPiat(PoaActivityPiat $piat) {

        if($piat) {
            $response = $this->ajaxDispatch(new DeletePoaActivityPiat($piat));

            if ($response['success']) {
                flash(trans_choice('messages.success.deleted', 0, ['type' => trans_choice('poa.piat_matrix_tag', 1)]))->success();
            } else {
                flash($response['message'])->error();
            }
        }

        return redirect()->route('activities.edit', ['activity' => $piat->id_poa_activities]);
    }
}
