<div wire:ignore.self class="modal fade in" id="poa-edit-activity-weight-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">{{ __('general.poa_edit_activity_weight_title') }}</h5>
                <button wire:click="resetForm" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <form wire:submit.prevent="submit" method="post" autocomplete="off">
                <div class="modal-body">
                    <x-form.modal.text id="poaActivityCost" label="{{ __('general.poa_activity_cost') }}" class="form-group"
                                       placeholder="{{ __('general.form.enter', ['field' => __('general.poa_activity_cost')]) }}">
                    </x-form.modal.text>

                    <x-form.modal.select id="poaActivityImpact" label="{{ __('general.poa_activity_impact') }}" required="required" class="form-group">
                        <option value="">{{ __('general.form.select.field', ['field' => __('general.poa_activity_impact')]) }}</option>
                        <option value="3">{{ __('general.poa_activity_category_high') }}</option>
                        <option value="2">{{ __('general.poa_activity_category_medium') }}</option>
                        <option value="1">{{ __('general.poa_activity_category_low') }}</option>
                    </x-form.modal.select>

                    <x-form.modal.select id="poaActivityComplexity" label="{{ __('general.poa_activity_complexity') }}" required="required" class="form-group">
                        <option value="">{{ __('general.form.select.field', ['field' => __('general.poa_activity_complexity')]) }}</option>
                        <option value="3">{{ __('general.poa_activity_category_high') }}</option>
                        <option value="2">{{ __('general.poa_activity_category_medium') }}</option>
                        <option value="1">{{ __('general.poa_activity_category_low') }}</option>
                    </x-form.modal.select>
                    <div class="modal-footer justify-content-center">
                        <x-form.modal.footer wirecancelevent="resetForm"></x-form.modal.footer>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>