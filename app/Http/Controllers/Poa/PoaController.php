<?php

namespace App\Http\Controllers\Poa;

use App\Abstracts\Http\Controller;
use App\Jobs\Poa\CreatePoa;
use App\Jobs\Poa\DeletePoa;
use App\Jobs\Poa\DeletePoaActivityTemplate;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use App\Models\Poa\PoaActivityTemplate;
use App\Models\Poa\PoaIndicatorGoalChangeRequest;
use App\Models\Poa\PoaProgram;
use App\States\Poa\InProgress;
use App\States\Poa\Planning;
use App\Traits\Jobs;

class PoaController extends Controller
{
    use Jobs;

    private $newProgramId = [];

    public function index()
    {
        $currentYear = (int)date('Y');
        $nextYear = $currentYear + 1;
        $poaExits = Poa::whereIn('year', [$currentYear, $nextYear])->count();
        $poas = Poa::collect();
        return view('modules.poa.poas.list', compact('poas'))
            ->with('poaExits', $poaExits);
    }

    public function reports()
    {
        return view('modules.poa.poas.reports');
    }

    public function changeControl($poaId = null)
    {
        return view('modules.poa.poas.changeControl')->with('poaId', $poaId);
    }

    public function config($poaId = null)
    {
        $poa = Poa::find($poaId);
        return view('modules.poa.poas.config', compact('poa'))
            ->with('poaId', $poaId);
    }


    public function store()
    {
        $currentYear = date('Y');
        $name = __('general.title.new', ['type' => __('general.poa')]);
        $userInCharge = user()->id;
        $currentPoa = Poa::enabled()->where('year', $currentYear)->first();

        if ($currentPoa) {
            $currentYear += 1;
        }

        $data = [
            'year' => $currentYear,
            'name' => $name . ' ' . $currentYear,
            'user_id_in_charge' => $userInCharge,
            'status' => InProgress::label(),
            'phase' => Planning::label(),
            'company_id' => session('company_id'),
        ];

        $response = $this->ajaxDispatch(new CreatePoa($data));

        if ($response['success']) {
            flash(trans_choice('messages.success.added', 0, ['type' => __('general.poa')]))->success();
            return redirect()->route('poa.poas');
        } else {
            flash($response['message'])->error();
        }
    }

    public function replicate($poaId)
    {
        $currentYear = (int)date('Y');
        $currentYearPoa = Poa::where('year', $currentYear)->first();
        if ($currentYearPoa) {
            $poaYear = $currentYear + 1;
        } else {
            $poaYear = $currentYear;
        }

        $modelPoa = Poa::find($poaId);
        if ($modelPoa) {
            $poa_replicate = $modelPoa->replicate()->fill([
                'year' => $poaYear,
                'name' => __('general.title.new', ['type' => __('general.poa')]) . ' ' . $poaYear,
                'progress' => 0,
                'status' => InProgress::label(),
                'phase' => Planning::label(),
                'reviewed' => false
            ]);
            
            $poa_replicate->push(); //set in poa_poas table

            $dep = $modelPoa->departments;
            foreach($dep as $d) {
                $poa_replicate->departments()->syncWithoutDetaching($d);
            }

            $modelPoa->load('programs.poaActivities.poaActivityIndicator');
            $modelPoa->load('configs');
            $relations = $modelPoa->getRelations();
            foreach ($relations as $key => $relation) {
                foreach ($relation as $relationRecord) {
                    if ($key == 'programs') {
                        $newRelationship = $relationRecord->replicate()->fill([
                            'progress' => 0
                        ]);
                        $newRelationship->poa_id = $poa_replicate->id;
                        $newRelationship->push(); //set in poa_programs table

                        array_push($this->newProgramId, $newRelationship->id);
                        $modelPoaPrograms = $relationRecord->activities;
                        foreach ($modelPoaPrograms as $relationItem) {

                            $newRelationshipActivity = $relationItem->replicate();
                            
                            $newRelationshipActivity->push(); //set to activity_log table
                        }
                        $modelPoaActivities = $relationRecord->poaActivities;
                        foreach ($modelPoaActivities as $poaActivity) {
                            $newModelPoaActivities = $poaActivity->replicate()->fill([
                                'status' => PoaActivity::STATUS_SCHEDULED,
                                'progress' => 0
                            ]);

                            $newModelPoaActivities->poa_program_id = $newRelationship->id;
                            $newModelPoaActivities->push(); //set to poa_activities table

                            $modelPoaActivitiesIndicators = $poaActivity->poaActivityIndicator;
                            foreach ($modelPoaActivitiesIndicators as $poaActivityIndicator) {
                                $newModelPoaActivitiesIndicators = $poaActivityIndicator->replicate()->fill([
                                    'progress' => 0,
                                    'men_progress' => 0,
                                    'women_progress' => 0
                                ]);

                                $newModelPoaActivitiesIndicators->poa_activity_id = $newModelPoaActivities->id;

                                $newModelPoaActivitiesIndicators->push(); //set to poa_activities_indicators table
                            }
                        }
                    } else if ($key == 'configs') {
                        if(count($this->newProgramId) > 0) {
                            foreach ($this->newProgramId as $newId) {
                                $newRelationship = $relationRecord->replicate();
                                $newRelationship->poa_id = $poa_replicate->id;
                                $newRelationship->program_id = $newId;

                                $newRelationship->push(); //set to poa_indicator_config table
                            }
                        }
                    }
                }
            }
            flash(trans('general.poa_replicate_title'))->success();

            return redirect()->route('poa.poas');
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => trans('general.poa_replicate_title_error'), 'icon' => 'info']);
        }
    }

    public function showPrograms($poaId)
    {
        $poa = Poa::find($poaId);
        return view('modules.poa.poas.programs', compact('poa'))
            ->with('poaId', $poaId);
    }

    public function goalChangeRequest()
    {
        $requests = [];
        $activities = PoaActivity::get()->groupBy('plan_detail_id');
        foreach ($activities as $activity) {
            foreach ($activity as $item) {
                $listRequests = PoaIndicatorGoalChangeRequest::whereIn('poa_activity_indicator_id', $item->poaActivityIndicator->pluck('id'))
                    ->get()->groupBy('request_number');
                foreach ($listRequests as $goalRequest) {
                    if ($goalRequest->first()->status === PoaIndicatorGoalChangeRequest::STATUS_OPEN) {
                        $element = [];
                        $element['id'] = $goalRequest->first()->id;
                        $element['date'] = $goalRequest->first()->created_at->format('F j, Y');
                        $element['activity'] = $goalRequest->first()->poaActivity->poaActivity->name;
                        $element['indicator'] = $goalRequest->first()->indicator->name;
                        $element['number_requests'] = $goalRequest->count();
                        $element['user'] = $goalRequest->first()->requestUser->getFullName();
                        $element['status'] = $goalRequest->first()->status;
                        $element['poa'] = $goalRequest->first()->poaActivity->poaActivity->program->poa->name;
                        $element['company'] = $goalRequest->first()->poaActivity->poaActivity->program->poa->company->name;
                        array_push($requests, $element);
                    }

                }
            }
        }
        return view('modules.poa.poas.goal-requests', compact('requests'));
    }

    public function manageCatalogActivities() {

        $poaActTemplModel = new PoaActivityTemplate();
        // $poaActTempl = $poaActTemplModel->all()->sortDesc();
        $poaActTempl = $poaActTemplModel->collect();

        return view('modules.poa.activity.catalog', compact('poaActTempl'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Poa $poa
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Poa $poa): \Illuminate\Http\RedirectResponse
    {
        if ($poa->status != Poa::STATUS_IN_PROGRESS || $poa->programs->count() > 0 || $poa->poaIndicatorConfigs->count() > 0) {
            flash('No se puede eliminar Poa')->error();
            return redirect()->route('poa.poas');
        }
        $response = $this->ajaxDispatch(new DeletePoa($poa));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 0, ['type' => trans_choice('general.poa', 1)]))->success();
        } else {
            flash($response['message'])->error();
        }
        return redirect()->route('poa.poas');
    }

    public function deleteCatalogActivities($id) {
        $activityCatalog = PoaActivityTemplate::find($id);

        if($activityCatalog) {
            $response = $this->ajaxDispatch(new DeletePoaActivityTemplate($activityCatalog));
            if ($response['success']) {
                flash(trans_choice('messages.success.deleted', 0, ['type' => trans_choice('general.poa_activity_catalog_create', 1)]))->success();
            } else {
                flash($response['message'])->error();
                return;
            }
        }

        return redirect()->route('poa.manage_catalog_activities');
    }
}
