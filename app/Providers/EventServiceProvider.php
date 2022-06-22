<?php

namespace App\Providers;

use App\Events\ActionStakeholderCreated;
use App\Events\ActivityProcessed;
use App\Events\IndicatorProccessed;
use App\Events\Menu\AdminCreated;
use App\Events\Menu\AuditCreated;
use App\Events\Menu\BudgetCreated;
use App\Events\Menu\CommonCreated;
use App\Events\Menu\IndicatorCreated;
use App\Events\Menu\PoaCreated;
use App\Events\Menu\ProcessCreated;
use App\Events\Menu\ProjectCreated;
use App\Events\Menu\RiskCreated;
use App\Events\Menu\StrategyCreated;
use App\Events\PoaActivityWeightChanged;
use App\Events\ProjectActivityWeightChanged;
use App\Events\ProjectColorUpdated;
use App\Events\ProjectStatusUpdated;
use App\Events\ProjectSubsidiaryUpdated;
use App\Events\ProjectUpdatedThresholds;
use App\Events\ResultCreated;
use App\Events\RiskCreatedEvent;
use App\Events\ServicesSelected;
use App\Events\TaskColorUpdated;
use App\Events\TaskCreated;
use App\Events\TaskDetailUpdated;
use App\Events\TaskOfResultCreated;
use App\Events\TaskUpdatedThresholds;
use App\Events\TaskUpdated;
use App\Events\TaskUpdatedCreateGoals;
use App\Jobs\Indicators\Thresholds\CreateThreshold;
use App\Listeners\ActionsTaskUpdated;
use App\Listeners\Auth\Login;
use App\Listeners\Auth\Logout;
use App\Listeners\CreateActivitiesOfServices;
use App\Listeners\CreateActivityOfResult;
use App\Listeners\CreateActivityOfTask;
use App\Listeners\CreateDeleteTasksOfProject;
use App\Listeners\CreateProjectActivity;
use App\Listeners\CreateProjectMemberSubsidiary;
use App\Listeners\CreateProjectReferentialBudget;
use App\Listeners\CreateProjectStateValidations;
use App\Listeners\CreateProjectThreshold;
use App\Listeners\CreateTask;
use App\Listeners\CreateTaskGoals;
use App\Listeners\CreateTaskOfRisk;
use App\Listeners\CreateThresholdsTask;
use App\Listeners\DuplicateActivity;
use App\Listeners\Menu\AddAdminItems;
use App\Listeners\Menu\AddAuditItems;
use App\Listeners\Menu\AddBudgetItems;
use App\Listeners\Menu\AddCommonItems;
use App\Listeners\Menu\AddIndicatorItems;
use App\Listeners\Menu\AddPoaItems;
use App\Listeners\Menu\AddProcessItems;
use App\Listeners\Menu\AddProjectItems;
use App\Listeners\Menu\AddRiskItems;
use App\Listeners\Menu\AddStrategyItems;
use App\Listeners\RegisterEventProject;
use App\Listeners\UpdateActivityProcessed;
use App\Listeners\UpdateActivityWeight;
use App\Listeners\UpdateCascadeProgressTasks;
use App\Listeners\UpdateChildsColor;
use App\Listeners\UpdatedColorResults;
use App\Listeners\UpdateFieldsThresholdProject;
use App\Listeners\UpdateFieldsThresholdTask;
use App\Listeners\UpdateIndicatorParent;
use App\Listeners\UpdateProjectActivityWeight;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Auth\Events\Logout as LogoutEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AdminCreated::class => [
            AddAdminItems::class,
        ],
        StrategyCreated::class => [
            AddStrategyItems::class,
        ],
        ProjectCreated::class => [
            AddProjectItems::class,
        ],
        BudgetCreated::class => [
            AddBudgetItems::class,
        ],
        AuditCreated::class => [
            AddAuditItems::class,
        ],
        ProcessCreated::class => [
            AddProcessItems::class,
        ],
        IndicatorCreated::class => [
            AddIndicatorItems::class,
        ],
        PoaCreated::class => [
            AddPoaItems::class,
        ],
        LoginEvent::class => [
            Login::class,
        ],
        LogoutEvent::class => [
            Logout::class,
        ],
        CommonCreated::class => [
            AddCommonItems::class,
        ],
        RiskCreated::class => [
            AddRiskItems::class,
        ],
        ActivityProcessed::class => [
            UpdateActivityProcessed::class,
        ],
        IndicatorProccessed::class => [
            UpdateIndicatorParent::class,
        ],
        \App\Events\ProjectCreated::class => [
            CreateProjectActivity::class,
            CreateProjectStateValidations::class,
            CreateProjectMemberSubsidiary::class,
            CreateProjectReferentialBudget::class,
            CreateProjectThreshold::class,
        ],
        ProjectStatusUpdated::class => [
            RegisterEventProject::class,
        ],
        ProjectColorUpdated::class => [
            UpdatedColorResults::class,
        ],
        ActionStakeholderCreated::class => [
            CreateTask::class,
        ],
        ResultCreated::class => [
            CreateActivityOfResult::class,
        ],
        TaskOfResultCreated::class => [
            CreateActivityOfTask::class,
        ],
        RiskCreatedEvent::class => [
            CreateTaskOfRisk::class,
        ],
        ServicesSelected::class => [
            CreateActivitiesOfServices::class
        ],
        TaskUpdatedCreateGoals::class => [
            CreateTaskGoals::class
        ],
        ProjectSubsidiaryUpdated::class => [
            CreateDeleteTasksOfProject::class
        ],
        PoaActivityWeightChanged::class => [
            UpdateActivityWeight::class
        ],
        ProjectActivityWeightChanged::class => [
            UpdateProjectActivityWeight::class
        ],
        TaskDetailUpdated::class => [
            UpdateCascadeProgressTasks::class
        ],
        TaskUpdated::class => [
            ActionsTaskUpdated::class
        ],
        ProjectUpdatedThresholds::class => [
            UpdateFieldsThresholdProject::class
        ],
        TaskUpdatedThresholds::class => [
            UpdateFieldsThresholdTask::class
        ],
        TaskColorUpdated::class => [
            UpdateChildsColor::class
        ],
        TaskCreated::class => [
            CreateThresholdsTask::class,
            DuplicateActivity::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
