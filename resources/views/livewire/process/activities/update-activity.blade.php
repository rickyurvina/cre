<div wire:ignore.self class="modal fade in" id="edit-process-activity" tabindex="-1" role="dialog"
     aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">{{ __('general.edit_template_strategy') }} {{__('general.activity')}}</h5>
                <button wire:click="resetForm" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                @if($activity)
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-nowrap">
                            <div class="flex-grow-1 w-100" style="overflow: hidden auto">
                                <div class="pl-2 content-detail">
                                    <div class="d-flex flex-wrap w-100">
                                        <x-label-detail>{{__('general.code')}}:</x-label-detail>
                                        <div class="detail">
                                            <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                                   class="\App\Models\Process\Activity"
                                                                                   field="code"
                                                                                   :rules="'required|max:5'"
                                                                                   type="text"
                                                                                   defaultValue="{{ $activity->code ?? ''}}"
                                                                                   :key="time().$activity->id"/>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap w-100">
                                        <x-label-detail>{{__('general.name')}}:</x-label-detail>
                                        <div class="detail">
                                            <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                                   class="\App\Models\Process\Activity"
                                                                                   field="name"
                                                                                   :rules="'required|max:200'"
                                                                                   type="text"
                                                                                   defaultValue="{{ $activity->name ?? ''}}"
                                                                                   :key="time().$activity->id"/>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap w-100">
                                        <x-label-detail>{{__('general.expected_result')}}:</x-label-detail>
                                        <div class="detail">
                                            <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                                   class="\App\Models\Process\Activity"
                                                                                   field="expected_result"
                                                                                   :rules="'required|max:500'"
                                                                                   type="textarea"
                                                                                   rows="5"
                                                                                   defaultValue="{{ $activity->expected_result ?? ''}}"
                                                                                   :key="time().$activity->id"/>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap w-100">
                                        <x-label-detail>{{__('general.specs')}}:</x-label-detail>
                                        <div class="detail">
                                            <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                                   class="\App\Models\Process\Activity"
                                                                                   field="specifications"
                                                                                   defaultValue="{{ $activity->specifications ?? ''}}"
                                                                                   :rules="'required|max:500'"
                                                                                   type="textarea"
                                                                                   rows="5"
                                                                                   :key="time().$activity->id"/>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap w-100">
                                        <x-label-detail>{{__('general.cares')}}:</x-label-detail>
                                        <div class="detail">
                                            <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                                   class="\App\Models\Process\Activity"
                                                                                   field="cares"
                                                                                   defaultValue="{{ $activity->cares ?? ''}}"
                                                                                   :rules="'required|max:500'"
                                                                                   type="textarea"
                                                                                   rows="5"
                                                                                   :key="time().$activity->id"/>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap w-100">
                                        <x-label-detail>{{__('general.procedures')}}:</x-label-detail>
                                        <div class="detail">
                                            <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                                   class="\App\Models\Process\Activity"
                                                                                   field="procedures"
                                                                                   defaultValue="{{ $activity->procedures ?? ''}}"
                                                                                   :rules="'required|max:500'"
                                                                                   type="textarea"
                                                                                   rows="5"
                                                                                   :key="time().$activity->id"/>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap w-100">
                                        <x-label-detail>{{__('general.equipment')}}:</x-label-detail>
                                        <div class="detail">
                                            <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                                   class="\App\Models\Process\Activity"
                                                                                   field="equipment"
                                                                                   defaultValue="{{ $activity->equipment ?? ''}}"
                                                                                   :rules="'required|max:500'"
                                                                                   type="textarea"
                                                                                   rows="5"
                                                                                   :key="time().$activity->id"/>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap w-100">
                                        <x-label-detail>{{__('general.supplies')}}:</x-label-detail>
                                        <div class="detail">
                                            <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                                   class="\App\Models\Process\Activity"
                                                                                   field="supplies"
                                                                                   defaultValue="{{ $activity->supplies ?? ''}}"
                                                                                   :rules="'required|max:500'"
                                                                                   type="textarea"
                                                                                   rows="5"
                                                                                   :key="time().$activity->id"/>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
