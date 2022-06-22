<div wire:ignore.self class="modal fade in" id="poa-edit-activity-progress-modal" tabindex="-1" role="dialog"
     aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">{{ __('general.poa_edit_activity_progress_title') }}
                    ({{ $indicatorUnitName }})</h5>
                <button wire:click="resetForm" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <form wire:submit.prevent="submitProgress()" method="post" autocomplete="off">
                <div class="modal-body">
                    @if(!$progressType)
                        <div class="form-group col-lg-12 required">
                            <div class="row">
                                @foreach($progress as $item)
                                    <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4 mb-1">
                                        <x-form.modal.text id="progress.{{ $loop->index }}.progress"
                                                           label="{{ $item['monthName'] }}"
                                                           required="required"
                                                           readonly="{{$loop->index+1> $currentMonth?'readonly':'' }}"
                                                           class="mb-0">
                                        </x-form.modal.text>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-xl-1" style="padding-top: 30px">
                                Planificado
                            </div>
                            <div class="col-xl-11 mb-1">
                                <div class="row">
                                    @foreach($progress as $item)
                                        <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4 mb-1">
                                            <x-form.modal.text id="progress.{{ $loop->index }}.goal"
                                                               label="{{ $item['monthName'] }}"
                                                               required="required"
                                                               readonly="readonly"
                                                               class="mb-0">
                                            </x-form.modal.text>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-1" style="padding-top: 30px">
                                {{ trans_choice('general.men', 2) }}
                            </div>
                            <div class="col-xl-11 mb-1">
                                <div class="row">
                                    @foreach($progress as $item)
                                        <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4 mb-1">
                                            <x-form.modal.text id="progress.{{ $loop->index }}.menProgress"
                                                               readonly="{{$loop->index+1> $currentMonth?'readonly':'' }}"
                                                               class="mb-0">
                                            </x-form.modal.text>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-1" style="padding-top: 30px">
                                {{ trans_choice('general.women', 2) }}
                            </div>
                            <div class="col-xl-11 mb-1">
                                <div class="row">
                                    @foreach($progress as $item)
                                        <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4 mb-1">
                                            <x-form.modal.text id="progress.{{ $loop->index }}.womenProgress"
                                                               label="" readonly="{{$loop->index+1> $currentMonth?'readonly':'' }}"
                                                               class="mb-0">
                                            </x-form.modal.text>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="modal-footer justify-content-center">
                        <x-form.modal.footer wirecancelevent="resetForm"></x-form.modal.footer>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
