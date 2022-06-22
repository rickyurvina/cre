<?php

namespace App\Http\Controllers\Poa;

use App\Abstracts\Http\Controller;
use App\Exports\DefaultHeaderReportExport;
use App\Exports\DefaultReportExport;
use App\Models\Indicators\Units\IndicatorUnits;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use App\Models\Poa\PoaProgram;
use App\Models\Strategy\PlanDetail;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class PoaReportController extends Controller
{

    public function index()
    {
        $cardReports = Config::get('constants.catalog.CARD_REPORTS');
        return view('modules.poa.reports.index', compact('cardReports'));
    }

    public function reachedPeople()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generatePoaReport($poa->id, IndicatorUnits::PEOPLE_REACHED);
        } else {
            $data['header'] = array();
            $data['detail'] = array();
        }
        $poas = Poa::select('year')->groupBy('year')->orderBy('year')->get();
        $quantityYears = $poas->count() * 2 + 1;
        return view('modules.poa.reports.reached-people.reached-people', compact('poas', 'data'))
            ->with('quantityYears', $quantityYears);
    }

    public function exportReachedPeople()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generatePoaReport($poa->id, IndicatorUnits::PEOPLE_REACHED);
        } else {
            $data['header'] = array();
            $data['detail'] = array();
        }
        $poas = Poa::select('year')->groupBy('year')->orderBy('year')->get();
        $quantityYears = $poas->count() * 2 + 1;
        $view = view('modules.poa.reports.reached-people.excel', compact('data', 'poas', 'quantityYears'));
        $response = Excel::download(new DefaultHeaderReportExport($view, trans('poa.reached_people')), trans('poa.reached_people') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }

    public function exportTrainedPeople()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generatePoaReport($poa->id, IndicatorUnits::TRAINED_PEOPLE);
        } else {
            $data['header'] = array();
            $data['detail'] = array();
        }
        $poas = Poa::select('year')->groupBy('year')->orderBy('year')->get();
        $quantityYears = $poas->count() * 2 + 1;
        $view = view('modules.poa.reports.trained-people.excel', compact('data', 'poas', 'quantityYears'));
        $response = Excel::download(new DefaultHeaderReportExport($view, trans('poa.trained_people')), trans('poa.trained_people') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }

    public function trainedPeople()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generatePoaReport($poa->id, IndicatorUnits::TRAINED_PEOPLE);
        } else {
            $data['header'] = array();
            $data['detail'] = array();
        }
        $poas = Poa::select('year')->groupBy('year')->orderBy('year')->get();
        $quantityYears = $poas->count() * 2 + 1;
        return view('modules.poa.reports.trained-people.trained-people', compact('poas', 'data', 'quantityYears'))
            ->with('quantityYears', $quantityYears);
    }

    public function exportSatisfactionLevel()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generatePoaReport($poa->id, IndicatorUnits::EVALUATION);
        } else {
            $data['header'] = array();
            $data['detail'] = array();
        }
        $poas = Poa::select('year')->groupBy('year')->orderBy('year')->get();
        $quantityYears = $poas->count() * 2 + 1;
        $view = view('modules.poa.reports.satisfaction-level.excel', compact('poas', 'data', 'quantityYears'));
        $response = Excel::download(new DefaultHeaderReportExport($view, trans('poa.satisfaction-level')), trans('poa.satisfaction-level') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }


    public function satisfactionLevel()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generatePoaReport($poa->id, IndicatorUnits::EVALUATION);
        } else {
            $data['header'] = array();
            $data['detail'] = array();
        }
        $poas = Poa::select('year')->groupBy('year')->orderBy('year')->get();
        $quantityYears = $poas->count() * 2 + 1;
        return view('modules.poa.reports.satisfaction-level.satisfaction-level', compact('poas', 'data'))
            ->with('quantityYears', $quantityYears);
    }

    public function exportProducts()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generatePoaReport($poa->id, IndicatorUnits::DOCUMENTS);
        } else {
            $data['header'] = array();
            $data['detail'] = array();
        }
        $poas = Poa::select('year')->groupBy('year')->orderBy('year')->get();
        $quantityYears = $poas->count() * 2 + 1;
        $view = view('modules.poa.reports.products.excel', compact('poas', 'data', 'quantityYears'));
        $response = Excel::download(new DefaultHeaderReportExport($view, trans('poa.products')), trans('poa.products') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;

    }

    public function products()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generatePoaReport($poa->id, IndicatorUnits::DOCUMENTS);
        } else {
            $data['header'] = array();
            $data['detail'] = array();
        }
        $poas = Poa::select('year')->groupBy('year')->orderBy('year')->get();
        $quantityYears = $poas->count() * 2 + 1;
        return view('modules.poa.reports.products.products', compact('poas', 'data'))
            ->with('quantityYears', $quantityYears);
    }

    public function exportGoals()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generateGoalPoaReport($poa->id);
            $dataTotal = $this->generateTotalGoalPoaReport($poa->id);
        } else {
            $data = array();
            $dataTotal = array();
        }
        $view = view('modules.poa.reports.goals.excel', compact('data', 'dataTotal'));
        $response = Excel::download(new DefaultHeaderReportExport($view, trans('poa.goals')), trans('poa.goals') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;

    }

    public function goals()
    {
        $poa = Poa::first();
        if ($poa) {
            $data = $this->generateGoalPoaReport($poa->id);
            $dataTotal = $this->generateTotalGoalPoaReport($poa->id);
        } else {
            $data = array();
            $dataTotal = array();
        }
        return view('modules.poa.reports.goals.goals', compact('data', 'dataTotal'));
    }

    public function exportActivityStatus()
    {
        $poa = Poa::first();
        $data = $this->generateActivityStatusReport($poa->id);
        $view = view('modules.poa.reports.activity-status.excel', compact('data'));
        $response = Excel::download(new DefaultHeaderReportExport($view, trans('poa.activity-status')), trans('poa.activity-status') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }

    public function activityStatus()
    {
        $poa = Poa::first();
        $data = $this->generateActivityStatusReport($poa->id);
        return view('modules.poa.reports.activity-status.activity-status', compact('data'));
    }

    private function generateActivityStatusReport($poaId)
    {
        $arrayReport = [];

        $poaPrograms = PoaProgram::where('poa_id', $poaId)
            ->orderBy('plan_detail_id')
            ->get();
        foreach ($poaPrograms as $program) {
            $poaActivities = PoaActivity::where('poa_program_id', $program->id)
                ->orderBy('indicator_id')
                ->get();
            foreach ($poaActivities as $activity) {
                $element = [];
                $element['programId'] = $program->id;
                $element['programName'] = $program->planDetail->name;
                $element['indicator'] = $activity->indicator->name;
                $element['activity'] = $activity->name;
                $element['responsible'] = $activity->responsible->name;
                $element['status'] = $activity->status;
                array_push($arrayReport, $element);
            }
        }
        return $arrayReport;
    }

    private function generatePoaReport($poaId, $type)
    {
        $arrayObjectiveSummary = [];
        $arrayObjective1Summary = [];
        $arrayObjective2Summary = [];

        //Indicator Unit ID for Satisfaction Level
        $indicatorUnitId = IndicatorUnits::where('abbreviation', $type)->first()->id;

        $poaPrograms = PoaProgram::where('poa_id', $poaId)->get();
        foreach ($poaPrograms as $program) {
            $poaActivities = PoaActivity::where('poa_program_id', $program->id)
                ->where('indicator_unit_id', $indicatorUnitId)
                ->get();
            foreach ($poaActivities as $activity) {
                $objective2Id = $activity->planDetail->parent->id;
                $key = array_search($objective2Id, array_column($arrayObjective2Summary, 'id'));
                if ($key === false) {
                    $element = [];
                    $element['id'] = $objective2Id;
                    $element['name'] = $activity->planDetail->parent->name;
                    $element['progressMen'] = 0;
                    $element['progressWomen'] = 0;
                    $element['goal'] = 0;
                    $element['advance'] = 0;
                    $element['progress'] = 0;
                    $element['documentProgress'] = 0;
                    $elements = array_push($arrayObjective2Summary, $element);
                    $key = $elements - 1;
                }
                switch ($type) {
                    case IndicatorUnits::PEOPLE_REACHED:
                    case IndicatorUnits::TRAINED_PEOPLE:
                        $menProgress = PoaActivityIndicator::where('poa_activity_id', $activity->id)
                            ->sum('men_progress');
                        $womenProgress = PoaActivityIndicator::where('poa_activity_id', $activity->id)
                            ->sum('women_progress');
                        $arrayObjective2Summary[$key]['progressMen'] += $menProgress;
                        $arrayObjective2Summary[$key]['progressWomen'] += $womenProgress;
                        break;
                    case IndicatorUnits::EVALUATION:
                        $goal = PoaActivityIndicator::where('poa_activity_id', $activity->id)
                            ->sum('goal');
                        $progress = PoaActivityIndicator::where('poa_activity_id', $activity->id)
                            ->sum('progress');
                        $arrayObjective2Summary[$key]['goal'] += $goal;
                        $arrayObjective2Summary[$key]['advance'] += $progress;
                        if ($arrayObjective2Summary[$key]['goal']) {
                            $arrayObjective2Summary[$key]['progress'] = $arrayObjective2Summary[$key]['advance'] / $arrayObjective2Summary[$key]['goal'];
                        } else {
                            $arrayObjective2Summary[$key]['progress'] = 0;
                        }
                        break;
                    case IndicatorUnits::DOCUMENTS:
                        $progress = PoaActivityIndicator::where('poa_activity_id', $activity->id)
                            ->sum('progress');
                        $arrayObjective2Summary[$key]['documentProgress'] += $progress;
                        break;
                }
            }
        }
        if (count($arrayObjective2Summary)) {
            foreach ($arrayObjective2Summary as $item) {
                $planDetail = PlanDetail::find($item['id']);
                $objective1Id = $planDetail->parent->id;
                $key = array_search($objective1Id, array_column($arrayObjective1Summary, 'id'));
                if ($key === false) {
                    $element = [];
                    $element['id'] = $objective1Id;
                    $element['name'] = $planDetail->parent->name;
                    $element['progressMen'] = $item['progressMen'];
                    $element['progressWomen'] = $item['progressWomen'];
                    $element['goal'] = $item['goal'];
                    $element['advance'] = $item['advance'];
                    $element['progress'] = $item['progress'];
                    $element['documentProgress'] = $item['documentProgress'];
                    array_push($arrayObjective1Summary, $element);
                } else {
                    $arrayObjective1Summary[$key]['progressMen'] += $item['progressMen'];
                    $arrayObjective1Summary[$key]['progressWomen'] += $item['progressWomen'];
                    $arrayObjective1Summary[$key]['goal'] += $item['goal'];
                    $arrayObjective1Summary[$key]['advance'] += $item['advance'];
                    if ($arrayObjective1Summary[$key]['goal']) {
                        $arrayObjective1Summary[$key]['progress'] = $arrayObjective1Summary[$key]['advance'] / $arrayObjective1Summary[$key]['goal'];
                    } else {
                        $arrayObjective1Summary[$key]['progress'] = 0;
                    }
                    $arrayObjective1Summary[$key]['documentProgress'] += $item['documentProgress'];
                }
            }
        }

        $arrayObjectiveSummary['header'] = $arrayObjective1Summary;
        $arrayObjectiveSummary['detail'] = $arrayObjective2Summary;
        return $arrayObjectiveSummary;
    }

    private function generateTotalGoalPoaReport($poaId)
    {

        //Indicator Unit ID for Satisfaction Level
        $poaPrograms = PoaProgram::where('poa_id', $poaId)->get();
        $total = [];
        foreach ($poaPrograms as $program) {
            $poaActivities = PoaActivity::where('poa_program_id', $program->id)
                ->get();
            foreach ($poaActivities as $activity) {
                $pacActivityIndicators = PoaActivityIndicator::where('poa_activity_id', $activity->id)->get();
                foreach ($pacActivityIndicators as $pacActivityIndicator) {
                    $val = 0;
                    if (isset($total[$program->id][$pacActivityIndicator->period]['menProgress'])) {
                        $val = $total[$program->id][$pacActivityIndicator->period]['menProgress'];
                    }
                    $total[$program->id][$pacActivityIndicator->period]['menProgress'] = $val + $pacActivityIndicator->men_progress;
                    $val = 0;
                    if (isset($total[$program->id][$pacActivityIndicator->period]['womenProgress'])) {
                        $val = $total[$program->id][$pacActivityIndicator->period]['womenProgress'];
                    }
                    $total[$program->id][$pacActivityIndicator->period]['womenProgress'] = $val + $pacActivityIndicator->women_progress;
                    $val = 0;
                    if (isset($total[$program->id][$pacActivityIndicator->period]['progress'])) {
                        $val = $total[$program->id][$pacActivityIndicator->period]['progress'];
                    }
                    $total[$program->id][$pacActivityIndicator->period]['progress'] = $val + $pacActivityIndicator->progress;
                    $val = 0;
                    if (isset($total[$program->id][$pacActivityIndicator->period]['goal'])) {
                        $val = $total[$program->id][$pacActivityIndicator->period]['goal'];
                    }
                    $total[$program->id][$pacActivityIndicator->period]['goal'] = $val + $pacActivityIndicator->goal;
                }
            }
        }
        return $total;
    }

    private function generateGoalPoaReport($poaId)
    {
        $arrayObjective2Summary = [];

        //Indicator Unit ID for Satisfaction Level
        $poaPrograms = PoaProgram::where('poa_id', $poaId)->get();
        foreach ($poaPrograms as $program) {
            $poaActivities = PoaActivity::where('poa_program_id', $program->id)
                ->get();
            foreach ($poaActivities as $activity) {
                $indicatorUnit = IndicatorUnits::where('id', $activity->indicator_unit_id)->first();
                $objective2Id = $activity->planDetail->parent->id;
                $element = [];
                $element['id'] = $activity->id;
                $element['idObjective'] = $objective2Id;
                $element['programId'] = $program->id;
                $element['name'] = "";
                $element['idGeneralObjective'] = 0;
                $element['programName'] = $activity->planDetail->name;
                $element['specificGoal'] = $activity->planDetail->parent->name;
                $element['indicatorName'] = $activity->indicator->name; //Indicator name
                $element['activityIndicatorName'] = $activity->name;
                if ($indicatorUnit != null) {
                    $element['indicatorUnit'] = trim($indicatorUnit->abbreviation);
                } else {
                    $element['indicatorUnit'] = "";
                }
                if (trim($indicatorUnit->abbreviation) == 'Doc' || trim($indicatorUnit->abbreviation) == 'Eva') {
                    $element['menWomenSeparate'] = 'No';
                } else {
                    $element['menWomenSeparate'] = 'Si';
                }
                $element['jan'] = [];
                $element['feb'] = [];
                $element['mar'] = [];
                $element['abr'] = [];
                $element['may'] = [];
                $element['jun'] = [];
                $element['jul'] = [];
                $element['aug'] = [];
                $element['sep'] = [];
                $element['oct'] = [];
                $element['nov'] = [];
                $element['dec'] = [];
                $elements = array_push($arrayObjective2Summary, $element);
                $poaActivityIndicators = PoaActivityIndicator::where('poa_activity_id', $activity->id)
                    ->get();
                foreach ($poaActivityIndicators as $poaActivityIndicator) {
                    $key = array_search($activity->id, array_column($arrayObjective2Summary, 'id'));
                    if (!($key === false)) {
                        $month = $poaActivityIndicator->period;
                        switch ($month) {
                            case 1:
                                $arrayObjective2Summary[$key]['jan']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['jan']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['jan']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['jan']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 2:
                                $arrayObjective2Summary[$key]['feb']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['feb']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['feb']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['feb']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 3:
                                $arrayObjective2Summary[$key]['mar']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['mar']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['mar']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['mar']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 4:
                                $arrayObjective2Summary[$key]['apr']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['apr']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['apr']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['apr']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 5:
                                $arrayObjective2Summary[$key]['may']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['may']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['may']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['may']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 6:
                                $arrayObjective2Summary[$key]['jun']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['jun']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['jun']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['jun']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 7:
                                $arrayObjective2Summary[$key]['jul']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['jul']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['jul']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['jul']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 8:
                                $arrayObjective2Summary[$key]['aug']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['aug']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['aug']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['aug']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 9:
                                $arrayObjective2Summary[$key]['sep']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['sep']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['sep']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['sep']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 10:
                                $arrayObjective2Summary[$key]['oct']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['oct']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['oct']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['oct']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 11:
                                $arrayObjective2Summary[$key]['nov']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['nov']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['nov']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['nov']['progress'] = $poaActivityIndicator->progress;
                                break;
                            case 12:
                                $arrayObjective2Summary[$key]['dec']['planned'] = $poaActivityIndicator->goal;
                                $arrayObjective2Summary[$key]['dec']['menProgress'] = $poaActivityIndicator->men_progress;
                                $arrayObjective2Summary[$key]['dec']['womenProgress'] = $poaActivityIndicator->women_progress;
                                $arrayObjective2Summary[$key]['dec']['progress'] = $poaActivityIndicator->progress;
                                break;
                        }
                    }
                }
            }
        }
        if (count($arrayObjective2Summary)) {
            foreach ($arrayObjective2Summary as $item) {
                $planDetail = PlanDetail::find($item['idObjective']);
                $key = array_search($item['id'], array_column($arrayObjective2Summary, 'id'));
                if (!($key === false)) {
                    $arrayObjective2Summary[$key]['name'] = $planDetail->parent->name;
                    $arrayObjective2Summary[$key]['idGeneralObjective'] = $planDetail->parent->id;
                }
            }
        }
        $sorted = $this->array_orderby($arrayObjective2Summary, 'idGeneralObjective', SORT_ASC, 'idObjective', SORT_ASC);
        return $sorted;
    }

    private function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}
