<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectStateValidations;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateProjectStateValidations
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\ProjectCreated $event
     * @return void
     */
    public function handle(ProjectCreated $event)
    {
        //
        $project = $event->project;
//        $validations =
//            [
//                'GAE' => ["value" => 0, "description" => null],
//                'GAF' => ["value" => 0, "description" => null],
//                'PLANIFICACION' => ["value" => 0, "description" => null],
//            ];
//        $validationGae =
//            [
//                'GAE' => ["value" => 0, "description" => null],
//            ];
//        $validationsGaePlanning =
//            [
//                'GAE' => ["value" => 0, "description" => null],
//                'PLANIFICACION' => ["value" => 0, "description" => null],
//            ];
//        $validationsGafPlanning =
//            [
//                'GAF' => ["value" => 0, "description" => null],
//                'PLANIFICACION' => ["value" => 0, "description" => null],
//            ];
//        $validationsPlanning =
//            [
//                'PLANIFICACION' => ["value" => 0, "description" => null],
//            ];
//        $settingsReview = [
//
//            'fields' => [
//                0 => 'name',
//                1 => 'problem_identified',
//                2 => 'general_objective'
//            ],
//            'relations' => [
//                0 => 'articulations',
//                1 => 'objectives',
//                2 => 'stakeholders',
//                3 => 'beneficiaries',
//                4 => 'locations',
//                5 => 'tasks'
//            ]
//
//        ];
//        $settingsPending = [
//
//            'fields' => [
//                0 => 'start_date',
//                1 => 'end_date'
//            ],
//            'relations' => [
//                0 => 'objectives',
//                1 => 'risks',
//                2 => 'members',
//                3 => 'funders'
//            ]
//
//        ];
        $data =
            [
//                [
//                    'state' => Project::PHASE_START_UP,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
//                [
//                    'state' => Project::PHASE_PLANNING,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
//                [
//                    'state' => Project::PHASE_IMPLEMENTATION,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
//                [
//                    'state' => Project::PHASE_CLOSING,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
//                [
//                    'state' => Project::STATE_IN_PROCESS,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
                [
                    'state' => Project::STATE_IN_REVIEW,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
                [
                    'state' => Project::STATE_FORMULATED,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
//                [
//                    'state' => Project::STATE_FINANCED,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
                [
                    'state' => Project::STATE_PENDING,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
                [
                    'state' => Project::STATE_GENERAL_COMPLETED,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
                [
                    'state' => Project::STATE_GENERAL_EXECUTION,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
                [
                    'state' => Project::STATE_GENERAL_EXTENSION,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
                [
                    'state' => Project::STATE_CLOSED,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
                [
                    'state' => Project::STATE_GENERAL_CANCELLED,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
                [
                    'state' => Project::STATE_GENERAL_DISCONTINUED,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
//                [
//                    'state' => Project::PHASE_CLOSING,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
            ];

        $dataInternalDevelopment =
            [
                [
                    'state' => Project::PHASE_PLANNING,
                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
                    'prj_project_id' => $project->id,
                    'user_id' => user()->id,
                ],
//                [
//                    'state' => Project::PHASE_IMPLEMENTATION,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
//                [
//                    'state' => Project::PHASE_CLOSING,
//                    'status' => ProjectStateValidations::STATUS_NO_VALIDATED,
//                    'prj_project_id' => $project->id,
//                    'user_id' => user()->id,
//                ],
            ];

        if ($project->type != Project::TYPE_INTERNAL_DEVELOPMENT) {
            foreach ($data as $item) {
                ProjectStateValidations::create($item);
            }
        } else {
            foreach ($dataInternalDevelopment as $item) {
                ProjectStateValidations::create($item);
            }
        }

    }
}
