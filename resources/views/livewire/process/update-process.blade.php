<div>
    <div class="modal fade" id="update-process-modal" tabindex="-1" aria-hidden="true" style="display: none;" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary color-white">
                    <h5 class="modal-title h4">{{ trans('general.edit').' '.trans_choice('general.module_process', 1)  }}</h5>
                    <button type="button" class="close" aria-label="Close" wire:click="closeModal()">
                        <span aria-hidden="true"><i class="far fa-times color-white"></i></span>
                    </button>
                </div>
                @if($process)
                    <div class="modal-body">
                        <div class="row">
                            <x-form.modal.text id="code" label="{{ __('general.code') }}" required="required"
                                               class="form-group col-6 required"
                                               placeholder="{{ __('general.form.enter', ['field' => __('general.code')]) }}">
                            </x-form.modal.text>

                            <x-form.modal.text id="name" label="{{ __('general.name') }}" required="required"
                                               class="form-group col-6 required"
                                               placeholder="{{ __('general.form.enter', ['field' => __('general.name')]) }}">
                            </x-form.modal.text>

                            <x-form.modal.textarea id="description"
                                                   label="{{ __('general.description') }}"
                                                   class="form-group col-12">
                            </x-form.modal.textarea>
                            <x-form.modal.selectwd id="departmentId"
                                                   label="{{ trans_choice('general.department',1) }}"
                                                   class="col-12 required">
                                <option value="">{{ trans('general.form.select.field', ['field' => trans_choice('general.department',1)]) }}</option>
                                @if($userDepartments)
                                    @foreach($userDepartments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.modal.selectwd>
                            <x-form.modal.selectwd id="owner_id"
                                                 label="{{ trans('general.process_owner') }}"
                                                 class="col-12 required">
                                <option value="">{{ trans('general.form.select.field', ['field' => trans('general.process_owner')]) }}</option>
                                @foreach($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->getFullName() }}</option>
                                @endforeach
                            </x-form.modal.selectwd>
                        </div>
                        <div class="card-footer text-muted py-2 text-center">
                            <a wire:click="closeModal()" class="btn btn-outline-secondary mr-1">
                                <i class="fas fa-times"></i> {{ trans('general.cancel') }}
                            </a>
                            <button class="btn btn-primary" wire:click="save()">
                                <i class="fas fa-save pr-2"></i> {{ trans('general.save') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>