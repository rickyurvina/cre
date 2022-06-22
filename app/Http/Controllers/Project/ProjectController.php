<?php

namespace App\Http\Controllers\Project;

use App\Abstracts\Http\Controller;
use App\Jobs\Projects\ProjectDeleteCommunication;
use App\Jobs\Projects\ProjectDeleteStakeholder;
use App\Models\Admin\Company;
use App\Models\Admin\Contact;
use App\Models\Auth\User;
use App\Models\Common\Catalog;
use App\Models\Projects\Activities\Task;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectEvaluation;
use App\Models\Projects\ProjectLearnedLessons;
use App\Models\Projects\ProjectRescheduling;
use App\Models\Projects\Stakeholders\ProjectCommunicationMatrix;
use App\Models\Projects\Stakeholders\ProjectStakeholder;
use App\Models\Risk\Risk;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Barryvdh\Snappy\Facades\SnappyPdf as PDFSnappy;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function React\Promise\map;
use Livewire\Component;
use App\Traits\Jobs;

class ProjectController extends Controller
{
    public $start;
    public $end;
    public $show;
    use Jobs;


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function index()
    {
        if (user()->cannot('project-crud-project') || user()->cannot('project-view-project') || user()->cannot('project-super-admin-project') ) {
            abort(403);
        } else {
            return view('modules.project.index');
        }
    }


    /**
     * Show the form for viewing the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(
            [
                'members.user.media',
                'subsidiaries',
                'areas.department',
                'risks',
                'stakeholders.actions',
                'feasibilityProject',
                'feasibilityCapabilityProject',
                'feasibilityProjectFeasibilityMatrix',
                'acquisitions',
                'beneficiaries',
                'indicators',
                'articulations',
                'objectives.results.indicators',
                'objectives.indicators',
                'location',
                'articulations.sourceProject',
                'articulations.targetPlan', 'articulations.targetRegisteredTemplate',
                'articulations.targetPlanDetail'
            ]);
        return view('modules.project.project-overview', ['project' => $project, 'page' => 'overview']);
    }

    public function showIndex(Project $project)
    {
        if (user()->cannot('manage-indexCard-project') || user()->cannot('view-indexCard-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(
                [
                    'objectives.results',
                    'location',
                    'objectives.results.indicators',
                    'objectives.indicators',
                    'articulations.sourceProject',
                    'articulations.targetPlan', 'articulations.targetRegisteredTemplate',
                    'articulations.targetPlanDetail'
                ]
            );
            $messages = Catalog::CatalogName('help_messages')->first()->details;

            return view('modules.project.formulation.index.index', ['project' => $project, 'page' => 'act', 'messages' => $messages]);
        }
    }


    /**
     * Show the form for viewing the specified resource.
     */
    public function showActivities(Project $project, $company = null)
    {
        if (user()->cannot('manage-timetable-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(['tasks']);
            return view('modules.project.project-activities', ['project' => $project, 'page' => 'activities', 'users' => User::get(), 'companies' => Company::get(), 'company' => $company]);
        }
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function showTeam(Project $project)
    {
        if (user()->cannot('manage-team-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(['subsidiaries', 'company', 'areas', 'members.user.media', 'members.role', 'members.place']);
            return view('modules.project.project-team', ['project' => $project, 'page' => 'team']);
        }
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function showLogicFrame(Project $project)
    {
        if (user()->cannot('manage-logicFrame-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(['objectives.results.indicators', 'objectives.indicators']);
            $messages = Catalog::CatalogName('help_messages')->first()->details;

            return view('modules.project.project-logic-frame', ['project' => $project, 'page' => 'logic_frame', 'messages' => $messages]);
        }
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function showRisk(Project $project)
    {
        if (user()->cannot('manage-risks-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(['risks.state']);
            $class=Project::class;
            $risks = Risk::where('riskable_id', $project->id)->where('riskable_type',$class)
                ->collect();
            $data = [];
            $scale_of_impacts = Catalog::catalogName('risk_impact_probability_catalog')->first()->details;

            foreach ($scale_of_impacts[0]->properties as $item) {
                $countRisk = $risks->where('probability', $item['probability'])->where('impact', $item['impact'])->count();
                $data[] = array_merge($item, ['radius' => $countRisk > 0 ? $countRisk : '']);
            }
            $messages = Catalog::CatalogName('help_messages')->first()->details;
            return view('modules.project.project-risks', ['project' => $project, 'page' => 'risks', 'risks' => $risks, 'projectId' => $project->id, 'scales' => $data, 'messages' => $messages,'class'=>$class]);
        }
    }


    /**
     * Show the form for viewing the specified resource.
     */
    public function showFiles(Project $project)
    {
        if (user()->cannot('manage-files-project' || 'view-files-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            return view('modules.project.project-files', ['project' => $project, 'page' => 'files']);
        }
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function showDocument(Project $project)
    {
        if (user()->cannot('manage-formulatedDocument-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $messages = Catalog::CatalogName('help_messages')->first()->details;
            return view('modules.project.formulation.document-formulated', ['project' => $project, 'page' => 'formulated_document', 'messages' => $messages]);
        }
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function showAcquisitions(Project $project)
    {
        if (user()->cannot('manage-acquisitions-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load([
                'acquisitions.product',
                'acquisitions.unit',
                'acquisitions.mode'
            ]);
            return view('modules.project.project-acquisitions', ['project' => $project, 'page' => 'acquisitions']);
        }
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function showReferentialBudget(Project $project)
    {
        if (user()->cannot('manage-referentialBudget-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $messages = Catalog::CatalogName('help_messages')->first()->details;
            return view('modules.project.formulation.project-profile-referential-budget', ['project' => $project, 'page' => 'budget', 'messages' => $messages]);
        }
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function showEvents(Project $project)
    {
        if (user()->cannot('view-events-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            return view('modules.project.project-events', ['project' => $project, 'page' => 'events']);
        }
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function showSummary(Project $project)
    {
        if (user()->cannot('view-summary-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(
                [
                    'members.user.media',
                    'risks',
                    'stakeholders',
                    'beneficiaries',
                    'articulations',
                    'articulations.sourceProject',
                    'articulations.targetPlan',
                    'articulations.targetRegisteredTemplate',
                    'articulations.targetPlanDetail',
                    'objectives.results.indicators',
                    'objectives.results',
                    'funders',
                    'cooperators',
                    'location',
                    'objectives.results',
                ]);
            $plans = [];

            if ($project->estimated_time) {
                $time = explode(',', $project->estimated_time)[3];
            }
            if ($project->estimated_time) {
                $time = explode(',', $project->estimated_time)[3];
                foreach ($project->objectives as $objective) {
                    foreach ($objective->results as $result) {
                        if ($result->planning) {
                            $plans[$result->id] = $result->planning;
                        }
                    }
                }
            }
            $messages = Catalog::CatalogName('help_messages')->first()->details;
            return view('modules.project.formulation.project-profile-summary', ['project' => $project, 'page' => 'summary', 'time' => $time ?? 0, 'plans' => $plans, 'messages' => $messages]);
        }
    }

    /**
     * @param $id
     * @return RedirectResponse|void
     */
    public function deleteStakeholder($id)
    {
        $response = $this->ajaxDispatch(new ProjectDeleteStakeholder($id));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 1, ['type' => trans_choice('general.stakeholder', 0)]))->success();
            return redirect()->back();
        } else {
            flash($response['message'])->error()->livewire($this);
        }
    }

    /**
     * @param Project $project
     * @return StreamedResponse
     */
    public function reportProfile(Project $project)
    {
        $project->load(
            [
                'members.user.media',
                'risks',
                'stakeholders',
                'beneficiaries',
                'articulations',
                'articulations.sourceProject',
                'articulations.targetPlan',
                'articulations.targetRegisteredTemplate',
                'articulations.targetPlanDetail',
                'objectives.results.indicators',
                'objectives.results',
                'funders',
                'cooperators',
                'location',
                'objectives.results',
            ]);
        $plans = [];
        if ($project->estimated_time) {
            $time = explode(',',$project->estimated_time)[3]??0 ;
            foreach ($project->objectives as $objective) {
                foreach ($objective->results as $result) {
                    if ($result->planning) {
                        $plans[$result->id] = $result->planning;
                    }
                }
            }
        }
        setlocale(LC_TIME, 'es_ES.utf8');
        $date = ucfirst(strftime('%B %Y'));
//        return view('modules.project.reports.profile', ['project'=>$project, 'time' => $time ?? 0, 'plans' => $plans]);
        $pdf = PDFSnappy::loadView('modules.project.reports.profile', ['project' => $project, 'time' => $time ?? 0, 'plans' => $plans]);
        $pdf->setOption('margin-left', 10);
        $pdf->setOption('margin-top', 10);
        $pdf->setOption('margin-right', 10);
        $pdf->setOption('dpi', 300);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'PerfilProyecto.pdf');
    }

    public function reportConstitutionalAct(Project $project)
    {
        $project->load(
            [
                'members.user.media',
                'risks',
                'stakeholders',
                'beneficiaries',
                'articulations',
                'articulations.sourceProject',
                'articulations.targetPlan',
                'articulations.targetRegisteredTemplate',
                'articulations.targetPlanDetail',
                'objectives.results.indicators',
                'objectives.results',
                'funders',
                'cooperators',
                'location',
                'objectives.results',
            ]);
        $plans = [];
        if ($project->estimated_time) {
            $time = explode(',',$project->estimated_time)[3]??0;
            $years=$time/12;
            foreach ($project->objectives as $objective) {
                foreach ($objective->results as $result) {
                    if ($result->planning) {
                        $plans[$result->id] = $result->planning;
                    }
                }
            }
        }
        setlocale(LC_TIME, 'es_ES.utf8');
        $date = ucfirst(strftime('%B %Y'));
//        return view('modules.project.reports.constitutional_act', ['project'=>$project, 'time' => $time ?? 0, 'plans' => $plans,'years'=>$years]);
        $pdf = PDFSnappy::loadView('modules.project.reports.constitutional_act', ['project' => $project, 'time' => $time ?? 0, 'plans' => $plans,'years'=>$years]);
        $pdf->setOption('margin-left', 10);
        $pdf->setOption('margin-top', 10);
        $pdf->setOption('margin-right', 10);
        $pdf->setOption('dpi', 300);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'ActaConstitución.pdf');
    }


    /**
     * Show the form for viewing the specified resource.
     */
    public function showStakeholder(Project $project)
    {
        if (user()->cannot('manage-stakeholders-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $data = array();
            $stakeholders = ProjectStakeholder::where('prj_project_id', $project->id)
                ->collect();
            if ($stakeholders) {
                foreach ($stakeholders->groupBy('strategy') as $index => $stakeholder) {
                    switch ($index) {
                        case ProjectStakeholder::MONITOR:
                            $bajo = ProjectStakeholder::LOW;
                            $alto = ProjectStakeholder::LOW;
                            $color = '#0b7d03';
                            $color2 = '#ffffff';
                            break;

                        case ProjectStakeholder::KEEP_SATISFIED:
                            $bajo = ProjectStakeholder::LOW;
                            $alto = ProjectStakeholder::HIGH;
                            $color = '#5dbe24';
                            $color2 = '#ffffff';
                            break;
                        case ProjectStakeholder::KEEP_INFORMED:
                            $bajo = ProjectStakeholder::HIGH;
                            $alto = ProjectStakeholder::LOW;
                            $color = '#e17a2d';
                            $color2 = '#ffffff';
                            break;
                        case ProjectStakeholder::MANAGE_CAREFULLY:
                            $bajo = ProjectStakeholder::HIGH;
                            $alto = ProjectStakeholder::HIGH;
                            $color = '#ca0101';
                            $color2 = '#ffffff';
                            break;
                        default:
                            $alto = '';
                            $bajo = '';
                            $color = '#0b7d03';
                            $color2 = '#ffffff';
                            break;
                    }
                    $data[] = [
                        "x" => $bajo,
                        "y" => $alto,
                        "result" => $index,
                        "color" => $color,
                        "color2" => $color2,
                        "value" => $stakeholder->count(),
                    ];
                }
            }
            $messages = Catalog::CatalogName('help_messages')->first()->details;

            return view('modules.project.project-stakeholders', ['project' => $project, 'page' => 'stakeholders', 'stakeholders' => $stakeholders, 'data' => $data, 'messages' => $messages]);
        }
    }

    public function communicationMatrix(Project $project)
    {
        if (user()->cannot('manage-communication-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load([
                'stakeholders.interested',
                'stakeholders.communications'
            ]);
            $stakeholders = $project->stakeholders->pluck('id');
            $communications = ProjectCommunicationMatrix::whereIn('prj_project_stakeholder_id', $stakeholders)
                ->when(!(user()->hasRole('super-admin')), function ($q) {
                    $s = user()->id;
                    $q->where('user_id', user()->id)
                        ->orWhere('prj_project_stakeholder_id', user()->id);
                })
                ->collect();
            return view('modules.project.project-communications', ['communications' => $communications, 'page' => 'communications', 'project' => $project]);
        }
    }

    /**
     * @param $id
     * @return RedirectResponse|void
     */
    public function deleteCommunication($id)
    {
        $response = $this->ajaxDispatch(new ProjectDeleteCommunication($id));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 1, ['type' => trans_choice('general.communication', 0)]))->success();
            return redirect()->back();
        } else {
            flash($response['message'])->error()->livewire($this);
        }
    }

    public function showActivitiesLogicFrame(Project $project, $resultId = null)
    {
        if (user()->cannot('manage-activities-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load([
                'tasks',
                'objectives.results',
            ])->when(!(user()->hasRole('super-admin')), function ($q) {
                $q->where('owner_id', user()->id);
            });
            return view('modules.project.project-activities_results', ['page' => 'activities_results', 'project' => $project]);
        }
    }

    public function lessonsLearned(Project $project)
    {
        if (user()->cannot('manage-learnedLessons-project' && 'view-learnedLessons-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load([
                'lessonsLearned'
            ]);
            return view('modules.project.project-lessons-learned', ['page' => 'lessons_learned', 'project' => $project]);
        }
    }

    public function deleteLesson(int $id)
    {
        $lesson = ProjectLearnedLessons::find($id);
        $projectId = $lesson->project->id;
        $lesson->delete();
        flash('Eliminado exitosamente')->success();
        return redirect()->route('projects.lessons_learned', $projectId);
    }

    public function showValidations(Project $project)
    {
        if (user()->cannot('manage-validations-project' || 'view-validations-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load([
                'responsible','stateValidations.user','members','tasks',
                'subsidiaries','areas','risks','stakeholders',
                'acquisitions','beneficiaries','indicators','articulations',
                'objectives','comments','locations',
                'funders','cooperators','referentialBudgets',
                'location','lessonsLearned','reschedulings','evaluations'
            ]);
            return view('modules.project.project-validations', ['page' => 'validations', 'project' => $project]);
        }
    }

    public function showReschedulings(Project $project)
    {
        if (user()->cannot('manage-reschedulings-project' || 'view-reschedulings-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(['reschedulings']);
            return view('modules.project.project-reschedulings', ['page' => 'reschedulings', 'project' => $project]);
        }
    }

    public function deleteRescheduling(int $id)
    {
        $rescheduling = ProjectRescheduling::find($id);
        $projectId = $rescheduling->project->id;
        $rescheduling->delete();
        flash('Eliminado exitosamente')->success();
        return redirect()->route('projects.reschedulings', $projectId);
    }

    public function indexLessons()
    {
        return view('modules.project.indexLessons', ['page' => 'lessons']);
    }

    public function showEvaluations(Project $project)
    {
        if (user()->cannot('manage-evaluations-project' || 'view-evaluations-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(['evaluations']);
            return view('modules.project.project-evaluations', ['page' => 'evaluations', 'project' => $project]);
        }
    }

    public function deleteEvaluation(int $id)
    {
        $evaluation = ProjectEvaluation::find($id);
        $projectId = $evaluation->project->id;
        $evaluation->delete();
        flash('Eliminado exitosamente')->success();
        return redirect()->route('projects.evaluations', $projectId);
    }

    public function showCalendar(Project $project)
    {
        $canAddActivity = true;
        if (user()->cannot('manage-calendar-project') || user()->cannot('project-super-admin-project')) {
            abort(403);
        } else {
            $project->load(['tasks']);
            $activities = Task::where('project_id', $project->id)->where('parent', '!=', 'root')
                ->where('type', '=', 'task')
                ->get();
            $data = [];
            foreach ($activities as $activity) {
                $data[] =
                    [
                        'title' => $activity->text,
                        'description' => $activity->description,
                        'start' => $activity->start_date->format('Y-m-d'),
                        'end' => $activity->end_date->format('Y-m-d'),
                        'color' => $activity->color ?? '#3A87AD',
                        'textColor' => '#ffffff',
                        'id' => $activity->id
                    ];
            }
            if ($project->phase == Project::PHASE_IMPLEMENTATION) {
                $canAddActivity = false;
            }
            return view('modules.project.project-calendar', compact('activities', 'data', 'canAddActivity'), ['page' => 'calendar', 'project' => $project]);
        }
    }

    public function showProjectBudgetDocument(Project $project)
    {
        return view('modules.project.budgetProject.projectBudgetDocument',compact('project'),['page' => 'budgetDocument']);
    }

}

