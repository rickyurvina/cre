<div>
    <div class="page-inner" style="margin-top: -2% !important;">
        <main id="js-page-content" role="main" class="w-100">
            <ol class="breadcrumb page-breadcrumb">
                <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
            </ol>
            <div class="subheader">
                @if(\Illuminate\Support\Facades\Auth::user()->contact_id==$user->contact->id)
                    {{$user->contact->id}}
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_contact_modal"
                       data-id="{{ $user->contact->id }}" class="dropdown-item">
                        <h1 class="subheader-title">
                            <i class='subheader-icon fal fa-edit'></i> {{ $user->contact->getFullName() }}
                        </h1>
                    </a>
                @else
                    <h1 class="subheader-title">
                        {{ $user->contact->getFullName() }}
                    </h1>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-6 col-xl-3 order-lg-1 order-xl-1">
                    <!-- profile summary -->
                    <div class="card mb-g rounded-top">
                        <div class="row no-gutters row-grid">
                            <div class="col-12">
                                <div class="d-flex flex-column align-items-center justify-content-center p-4">
                                    @if (is_object($user->picture))
                                        <img src="{{ Storage::url($user->picture->id) }}" class="rounded-circle width-2" alt="{{ $user->name }}">
                                    @else
                                        <img src="{{ asset_cdn("img/user.svg") }}" class="rounded-circle width-2" alt="{{ $user->name }}">
                                    @endif
                                    <h5 class="mb-0 fw-700 text-center mt-3">
                                        @if(\Illuminate\Support\Facades\Auth::user()->id==$user->contact->id)
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_contact_modal"
                                               data-id="{{ $user->contact->id }}" class="dropdown-item">
                                                {{$user->contact->getFullName()}}
                                            </a>
                                        @else
                                            {{$user->contact->getFullName()}}
                                        @endif

                                        <small class="text-muted mb-0">{{$user->contact->personal_notes}}</small>
                                    </h5>
                                    <div class="mt-4 text-center demo">
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#3b5998">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#38A1F3">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#0077B5">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#00AFF0">
                                            <i class="fab fa-skype"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="text-center py-3">
                                    <strong>{{trans('general.personal_information')}}</strong>
                                </div>
                                <a href="javascript:void(0);" class="d-flex flex-row align-items-center mb-1">
                                    <div class='icon-stack display-4 flex-shrink-0'>
                                        <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                        <i class="fas fa-mail-bulk icon-stack-1x opacity-100 color-primary-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <strong style="color: black">
                                            {{$user->contact->email}}
                                        </strong>
                                    </div>
                                </a>
                                <a href="javascript:void(0);" class="d-flex flex-row align-items-center mb-1">
                                    <div class='icon-stack display-4 flex-shrink-0'>
                                        <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                        <i class="fas fa-phone-plus icon-stack-1x opacity-100 color-primary-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <strong style="color: black">
                                            {{$user->contact->personal_phone}}
                                        </strong>
                                    </div>
                                </a>
                                <a href="javascript:void(0);" class="d-flex flex-row align-items-center mb-1">
                                    <div class='icon-stack display-4 flex-shrink-0'>
                                        <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                        <i class="fas fa-calendar-check icon-stack-1x opacity-100 color-primary-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <strong style="color: black">
                                            {{$user->contact->date_birth }}
                                        </strong>
                                    </div>
                                </a>
                                <div class="panel-container show">
                                    <div class="collapse" id="collapseExample">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#user-show-work-experience"
                                           data-id="{{$user->id}}" class="d-flex flex-row align-items-center mb-1">
                                            <div class='icon-stack display-4 flex-shrink-0'>
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-chart-network icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <strong style="color: black">
                                                    Experiencia Laboral
                                                </strong>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" class="d-flex flex-row align-items-center mb-1">

                                            <div class='icon-stack display-4 flex-shrink-0'>
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-graduation-cap icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <strong style="color: black">
                                                    {{$user->contact->job_title}}
                                                </strong>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#user-show-competencies"
                                           data-id="{{$user->id}}" class="d-flex flex-row align-items-center mb-1">
                                            <div class='icon-stack display-4 flex-shrink-0'>
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-handshake icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <strong style="color: black">
                                                    {{trans('general.competencies')}}
                                                </strong>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#user-show-skills"
                                           data-id="{{$user->id}}" class="d-flex flex-row align-items-center mb-1">
                                            <div class='icon-stack display-4 flex-shrink-0'>
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-hands-helping icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <strong style="color: black">
                                                    {{trans('general.working_skills')}}
                                                </strong>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" class="d-flex flex-row align-items-center mb-1">
                                            <div class='icon-stack display-4 flex-shrink-0'>
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-money-bill icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <strong style="color: black">
                                                Costo contrato: ${{$user->contact->employer_cost}}
                                                </strong>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" class="d-flex flex-row align-items-center mb-1">
                                            <div class='icon-stack display-4 flex-shrink-0'>
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-money-check icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <strong style="color: black">
                                                  Contrato:  {{$user->contact->contract_type}} Fecha Inicio/Fin:{{$user->contact->contract_start}}- {{$user->contact->contract_end}}

                                                </strong>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#user-show-files"
                                           data-id="{{$user->id}}" class="d-flex flex-row align-items-center mb-1">
                                            <div class='icon-stack display-4 flex-shrink-0'>
                                                <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                                <i class="fas fa-file icon-stack-1x opacity-100 color-primary-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <strong style="color: black">
                                                    {{trans('general.files')}}
                                                </strong>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3 text-center">
                                            <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"
                                               class="btn-link font-weight-bold">{{trans('general.see_more')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center py-3">
                                    <h5 class="mb-0 fw-700">
                                        {{$user->comments->count()}}
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#user-show-comments"
                                           data-id="{{$user->id}}" class="btn-link font-weight-bold">{{trans('general.comments')}}</a> <span
                                                class="text-primary d-inline-block mx-3">&#9679;</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center py-3">
                                    <h5 class="mb-0 fw-700">
                                        {{$user->activityLog->count()}}
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#user-show-connections"
                                           data-id="{{$user->id}}" class="btn-link font-weight-bold">{{trans('general.connections')}}</a> <span
                                                class="text-primary d-inline-block mx-3">&#9679;</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 text-center">
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-info badge-pill">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 text-center">
                                    @foreach($user->companies as $company)
                                        <span class="badge badge-primary badge-pill">{{ $company->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- photos -->
                    <div class="card mb-g">
                        <div class="row row-grid no-gutters">
                            <div class="col-12">
                                <div class="p-3">
                                    <h2 class="mb-0 fs-xl">
                                        {{trans('general.poas')}}
                                    </h2>
                                </div>
                            </div>
                            @foreach($user->poas  as $poa)
                                <div class="col-6">
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="px-3 py-2 d-flex align-items-center chart">
                                            <span class="d-inline-block ml-2 text-muted mr-2">
                                                                               {{$poa->name}}
                                            </span>
                                            <div class="js-easy-pie-chart color-success-500 position-relative d-inline-flex align-items-center justify-content-center"
                                                 id="chartExecution"
                                                 data-percent="{{ number_format($poa->calcProgress(),1)  }}" data-piesize="50"
                                                 data-linewidth="5" data-linecap="butt">
                                                <div
                                                        class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-lg">
                                                    <span class="js-percent d-block text-dark"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card mb-g">
                        <div class="row row-grid no-gutters">
                            <div class="col-12">
                                <div class="p-3">
                                    <h2 class="mb-0 fs-xl">
                                        {{trans('general.activities_created_poas')}}
                                    </h2>
                                </div>
                            </div>
                            @foreach($user->activitiesPoa  as $activities)
                                @if($loop->iteration<=3)
                                    <div class="col-12">
                                        <div class="p-3">
                                            <div class="fw-500 fs-xs">
                                                <a href="javascript:void(0);" aria-expanded="false"
                                                   style="color:{{ $activities->getStatus()[2] }} "
                                                   wire:click="$emitTo('poa.poa-show-activity', 'open', {{ $activities->id }})">
                                                    {{$activities->name}}-{{$activities->program->poa->name}}
                                                </a>
                                            </div>
                                            <div class="progress progress-xs mt-2">
                                                <div class="progress-bar bg-primary-300 bg-primary-gradient {{$activities->progress()>50 ? ' bg-primary-300 bg-primary-gradient':' bg-danger-300 bg-warning-gradient' }}"
                                                     role="progressbar" style="width: {{$activities->progress()}}%"
                                                     aria-valuenow="80" aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="collapse" id="collapseActivities">
                                @foreach($user->activitiesPoa  as $activities)
                                    @if($loop->iteration>3)
                                        <div class="col-12">
                                            <div class="p-3">
                                                <div class="fw-500 fs-xs">
                                                    <a href="javascript:void(0);" aria-expanded="false"
                                                       style="color:{{ $activities->getStatus()[2] }} "
                                                       wire:click="$emitTo('poa.poa-show-activity', 'open', {{ $activities->id }})">
                                                        {{$activities->name}}-{{$activities->program->poa->name}}
                                                    </a>
                                                </div>
                                                <div class="progress progress-xs mt-2">
                                                    <div class="progress-bar bg-primary-300 bg-primary-gradient {{$activities->progress()>50 ? ' bg-primary-300 bg-primary-gradient':' bg-danger-300 bg-warning-gradient' }}"
                                                         role="progressbar" style="width: {{$activities->progress()}}%"
                                                         aria-valuenow="80" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-12">
                                <div class="p-3 text-center">
                                    <a data-toggle="collapse" href="#collapseActivities" role="button" aria-expanded="false" aria-controls="collapseActivities"
                                       class="btn-link font-weight-bold">{{trans('general.see_more')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-6 order-lg-3 order-xl-2">

                    <!-- post comment -->
                    <div class="card mb-g">
                        <div class="card-body">
                            <h2 class="mb-0 fs-xl">
                                {{trans('general.projects_in_charge')}}
                            </h2>
                        </div>
                        @foreach($user->projects as $item)
                            @if($loop->iteration<=3)
                                <a href="{{ route('projects.show', $item->id) }}" class="card border shadow-hover-5">
                                    <div class="card-body">
                                        <span class="fs-xl font-weight-bolder color-black">{{ $item->name }}</span>
                                        <p class="card-text color-fusion-50"{{ $item->description }}</p>
                                        <div class="progress progress-xs mt-3">
                                            <div class="progress-bar bg-danger-300 bg-warning-gradient" role="progressbar" style="width: 30%" aria-valuenow="30"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="profile-image-group mt-4">
                                            @foreach($item->members->take(2) as $member)
                                                @if (is_object($member->contact->picture))
                                                    <div class="img-item rounded-circle">
                                                        @if($loop->iteration == 2 && $item->members->count() > 2)
                                                            <span data-hasmore="+{{ $item->members->count() - 2 }}" class="profile-image-md rounded-circle">
                                            <img src="{{ Storage::url($member->contact->picture->id) }}"
                                                 class="profile-image-md" alt="{{ $member->contact->full_name }}">
                                        </span>
                                                        @else
                                                            <img src="{{ Storage::url($member->contact->picture->id) }}"
                                                                 class="profile-image-md" alt="{{ $member->contact->full_name }}">
                                                        @endif

                                                    </div>
                                                @else
                                                    <div class="img-item rounded-circle">
                                                        @if($loop->iteration == 2 && $item->members->count() > 2)
                                                            <span data-hasmore="+{{ $item->members->count() - 2 }}" class="profile-image-md rounded-circle">
                                            <img src="{{ asset_cdn("img/user.svg") }}" class="profile-image-md"
                                                 alt="{{ $member->contact->full_name }}">
                                        </span>
                                                        @else
                                                            <img src="{{ asset_cdn("img/user.svg") }}" class="profile-image-md"
                                                                 alt="{{ $member->contact->full_name }}">
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                        <div class="collapse" id="collapseProjects">
                            @foreach($user->projects as $item)
                                @if($loop->iteration>3)
                                    <a href="{{ route('projects.show', $item->id) }}" class="card border shadow-hover-5">

                                        <div class="card-body">
                                            <span class="fs-xl font-weight-bolder color-black">{{ $item->name }}</span>
                                            <p class="card-text color-fusion-50"{{ $item->description }}</p>
                                            <div class="progress progress-xs mt-3">
                                                <div class="progress-bar bg-danger-300 bg-warning-gradient" role="progressbar" style="width: 30%" aria-valuenow="30"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="profile-image-group mt-4">
                                                @foreach($item->members->take(2) as $member)
                                                    @if (is_object($member->contact->picture))
                                                        <div class="img-item rounded-circle">
                                                            @if($loop->iteration == 2 && $item->members->count() > 2)
                                                                <span data-hasmore="+{{ $item->members->count() - 2 }}" class="profile-image-md rounded-circle">
                                            <img src="{{ Storage::url($member->contact->picture->id) }}"
                                                 class="profile-image-md" alt="{{ $member->contact->full_name }}">
                                        </span>
                                                            @else
                                                                <img src="{{ Storage::url($member->contact->picture->id) }}"
                                                                     class="profile-image-md" alt="{{ $member->contact->full_name }}">
                                                            @endif

                                                        </div>
                                                    @else
                                                        <div class="img-item rounded-circle">
                                                            @if($loop->iteration == 2 && $item->members->count() > 2)
                                                                <span data-hasmore="+{{ $item->members->count() - 2 }}" class="profile-image-md rounded-circle">
                                            <img src="{{ asset_cdn("img/user.svg") }}" class="profile-image-md"
                                                 alt="{{ $member->contact->full_name }}">
                                        </span>
                                                            @else
                                                                <img src="{{ asset_cdn("img/user.svg") }}" class="profile-image-md"
                                                                     alt="{{ $member->contact->full_name }}">
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-12">
                            <div class="p-3 text-center">
                                <a data-toggle="collapse" href="#collapseProjects" role="button" aria-expanded="false" aria-controls="collapseProjects"
                                   class="btn-link font-weight-bold">{{trans('general.see_more')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-g">
                        <div class="card-body">
                            <h2 class="mb-0 fs-xl">
                                {{trans('general.comments')}}
                            </h2>
                        </div>
                        @foreach($user->comments as $comment)
                            @if($loop->iteration<=15)
                                <div class="card-body pb-0 px-4">

                                    <div class="d-flex flex-row pb-3 pt-2  border-top-0 border-left-0 border-right-0">
                                        <div class="d-inline-block align-middle mr-3">
                                            <span class="d-block mt-1"><i class="fas fa-comment"></i></span>
                                        </div>
                                        <h5 class="mb-0 flex-1 text-dark fw-500">
                                            {{ $comment->commentable->name }}
                                        </h5>
                                        <span class="text-muted fs-xs opacity-70">
                                                {{ $comment->updated_at->diffForHumans() }}
                                            </span>
                                    </div>
                                    <div class="pb-3 pt-2 border-top-0 border-left-0 border-right-0 text-muted">
                                        {!! $comment->comment !!}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <div class="collapse" id="collapseResponsePlans">
                            @foreach($user->comments as $comment)
                                @if($loop->iteration>15)
                                    <div class="card-body pb-0 px-4">
                                        <div class="d-flex flex-row pb-3 pt-2  border-top-0 border-left-0 border-right-0">
                                            <h5 class="mb-0 flex-1 text-dark fw-500">
                                                {{ $comment->commentable->name }}
                                            </h5>
                                            <span class="text-muted fs-xs opacity-70">
                                                {{ $comment->updated_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div class="pb-3 pt-2 border-top-0 border-left-0 border-right-0 text-muted">
                                            {!! $comment->comment !!}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-12">
                            <div class="p-3 text-center">
                                <a data-toggle="collapse" href="#collapseResponsePlans" role="button" aria-expanded="false" aria-controls="collapseResponsePlans"
                                   class="btn-link font-weight-bold">{{trans('general.see_more')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3 order-lg-2 order-xl-3">
                    <!-- rating -->
                    <div class="card mb-g">
                        <div class="row row-grid no-gutters">
                            <div class="col-12">
                                <div class="p-3">
                                    <h2 class="mb-0 fs-xl">
                                        {{trans('general.indicators')}}
                                    </h2>
                                </div>
                            </div>
                            @foreach($user->indicators as $indicator)
                                @if($loop->iteration<=5)
                                    <div class="col-12">
                                        <div class="p-3">
                                            <a href="javascript:void(0);" aria-expanded="false"
                                               wire:click="$emitTo('indicators.indicator-show', 'open', {{ $indicator->id }})">{{$indicator->name}}</a>
                                            <div class="progress progress-xs mt-2">
                                                <div class="progress-bar  {{$indicator->getStateIndicator()[1]>50 ? ' bg-primary-300 bg-primary-gradient':' bg-danger-300 bg-warning-gradient' }} "
                                                     role="progressbar" style="width: {{$indicator->getStateIndicator()[1]?? null}}%" aria-valuenow="80" aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="collapse" id="collapseIndicators">
                                @foreach($user->indicators as $indicator)
                                    @if($loop->iteration>5)
                                        <div class="col-12">
                                            <div class="p-3">
                                                <a href="javascript:void(0);" aria-expanded="false"
                                                   wire:click="$emitTo('indicators.indicator-show', 'open', {{ $indicator->id }})">{{$indicator->name}}</a>
                                                <div class="progress progress-xs mt-2">
                                                    <div class="progress-bar  {{$indicator->getStateIndicator()[1]>50 ? ' bg-primary-300 bg-primary-gradient':' bg-danger-300 bg-warning-gradient' }} "
                                                         role="progressbar" style="width: {{$indicator->getStateIndicator()[1]?? null}}%" aria-valuenow="80" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-12">
                                <div class="p-3 text-center">
                                    <a data-toggle="collapse" href="#collapseIndicators" role="button" aria-expanded="false" aria-controls="collapseIndicators"
                                       class="btn-link font-weight-bold">{{trans('general.see_more')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-g">
                        <div class="row row-grid no-gutters">
                            @foreach($user->responsePlans as $item)
                                <div class="card border-info mt-3 mb-3 w-100">
                                    <div class="card-header bg-info-500 bg-info-gradient">
                                        <div class="panel-hdr bg-transparent">
                                            <h2>
                                                {{ $item->name }}
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group" style="width: 10px !important;">
                                                <div class="h-100 d-flex align-items-center pr-1"
                                                     style="background-color: {{ $item->color ?? '#808080' }};"></div>
                                            </div>
                                            <div class="form-group col">
                                                <label><b>{{trans('general.start_date')}}</b></label>
                                                {{$item->start_date}}
                                            </div>
                                            <div class="form-group col">
                                                <label><b>{{trans('general.end_date')}}</b></label>
                                                {{$item->closing_date}}
                                            </div>
                                        </div>

                                        <div class="progress progress-xs">
                                            <div class="progress-bar" role="progressbar" style="width: 100%; background: #0a6ebd;"
                                                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <div class="form-row mt-2 mb-2">
                                            <div class="col">
                                                <h4><b>{{trans('general.actions_list')}}</b></h4>
                                            </div>
                                        </div>
                                        @if(count($item->actions) > 0 )
                                            <ul class="list-group">
                                                @foreach($item->actions as $item)
                                                    <li class="list-group-item p-0">
                                                        <div class="form-row">
                                                            <div style="width: 10px !important;">
                                                                <div class="h-100 d-flex align-items-center pr-1"
                                                                     style="background-color: {{ $item->responsePlan->color ?? '#808080' }};"></div>
                                                            </div>
                                                            <div class="col">
                                                                {{$item->name}}
                                                            </div>
                                                            <div class="col">
                                                                <label><b>{{trans('general.owner')}}</b></label>
                                                                {{$item->user->name}}
                                                            </div>
                                                            <div class="col">
                                                                <label><b>{{trans('general.implementation_date')}}</b></label>
                                                                {{$item->implementation_date}}
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <div class="card bg-transparent ml-2 mt-2 mb-2">
                                                        <div class="form-row mt-2 mb-2">
                                                            <div class="col">
                                                                <h4><b>{{trans('general.task_list')}}</b></h4>
                                                            </div>
                                                        </div>
                                                        <div class="progress progress-xs mb-2">
                                                            <div class="progress-bar bg-danger-gradient" role="progressbar" style="width: 100%;" aria-valuenow="100"
                                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        @if(count($item->tasks) > 0 )
                                                            <ul class="list-group">
                                                                @foreach($item->tasks as $item)
                                                                    <li class="list-group-item p-0">
                                                                        <div class="form-row">
                                                                            <div style="width: 10px !important;">
                                                                                <div class="h-100 d-flex align-items-center pr-1"
                                                                                     style="background-color: {{ $color ?? '#808080' }};"></div>
                                                                            </div>
                                                                            <div class="col" style="background-color: #50505026">
                                                                                {{$item->name}}
                                                                            </div>
                                                                            <div class="col" style="background-color: #50505026">
                                                                                <label><b>{{trans('general.owner')}}</b></label>
                                                                                {{$item->user->name}}
                                                                            </div>
                                                                            <div class="col" style="background-color: #50505026">
                                                                                <label><b>{{trans('general.date')}}</b></label>
                                                                                {{$item->task_date}}
                                                                            </div>

                                                                            <div class="col" style="background-color: #50505026">
                                                                                @if ($item->enabled)
                                                                                    <span class="badge badge-success">{{trans('general.enabled_')}}</span>
                                                                                @else
                                                                                    <span class="badge badge-danger">{{trans('general.disabled')}}</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <ul class="list-group">
                                                                <li class="list-group-item">
                                                                    <span data-filter-tags="reports file">{{trans('general.without_tasks_regsitered')}}</span>
                                                                </li>
                                                            </ul>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </ul>
                                        @else
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <span data-filter-tags="reports file">{{trans('general.without_actions_regsitered')}}</span>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <livewire:user.user-show-comments :id="$user"/>
    <livewire:user.user-show-connections :id="$user"/>
    <livewire:user.user-show-work-experience :id="$user"/>
    <livewire:user.user-show-skills :id="$user"/>
    <livewire:user.user-show-files :id="$user"/>
    <livewire:user.user-competencies :id="$user"/>
    <livewire:poa.poa-show-activity/>
    <div class="modal fade fade" id="indicator-show-modal" tabindex="-1" style="display: none;" role="dialog" aria-hidden="true">
        <livewire:indicators.indicator-show/>
    </div>
    <div wire:ignore.self>
        <livewire:admin.contact-edit-modal/>
    </div>
</div>
@push('page_script')
    <script>
        Livewire.on('toggleIndicatorShowModal', () => $('#indicator-show-modal').modal('toggle'));
        Livewire.on('toggleShowModal', () => $('#poa-show-activity-modal').modal('toggle'));
        Livewire.on('toggleContactEditModal', () => $('#edit_contact_modal').modal('toggle'));

        $('#edit_contact_modal').on('show.bs.modal', function (e) {
            let id = $(e.relatedTarget).data('id');
            //Livewire event trigger
            Livewire.emit('openContactEditModal', id);
        });

    </script>
@endpush