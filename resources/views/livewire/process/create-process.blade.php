<div>
    <div wire:ignore.self class="modal fade in" id="create-process-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">{{ trans('general.add_new').' '.trans_choice('general.module_process', 1)  }}</h5>
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="far fa-times color-white"></i></span>
                    </button>
                </div>
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
                        <x-form.modal.select id="ownerId"
                                               label="{{ trans('general.process_owner') }}"
                                               class="col-12 required">
                            <option value="">{{ trans('general.form.select.field', ['field' => trans('general.process_owner')]) }}</option>
                            @if($users)
                                @foreach($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->getFullName() }}</option>
                                @endforeach
                            @endif
                        </x-form.modal.select>

                    </div>
                    <div class="modal-footer justify-content-center">
                        <x-form.modal.footer wirecancelevent="resetForm" wiresaveevent="save"></x-form.modal.footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>