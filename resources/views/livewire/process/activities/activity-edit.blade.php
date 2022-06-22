<div class="modal fade" id="edit_activity_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ trans('general.edit') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times"></i></span>
                </button>
            </div>
            <form action="{{ route('activities.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">

                                <x-form.inputs.text type="text" id="specs"
                                                    label="{{ trans('general.specs') }}"
                                                    class="col-6 required"
                                                    value="{{ old('specs') }}"
                                                    placeholder="{{ trans('general.form.enter', ['field' => trans('general.specs')]) }}"/>

                                <x-form.inputs.text type="text" id="cares"
                                                    label="{{ trans('general.cares') }}"
                                                    class="col-6 required"
                                                    value="{{ old('cares') }}"
                                                    placeholder="{{ trans('general.form.enter', ['field' => trans('general.cares')]) }}"/>

                                <x-form.inputs.text type="text" id="procedures"
                                                    label="{{ trans('general.procedures') }}"
                                                    class="col-6 required"
                                                    value="{{ old('procedures') }}"
                                                    placeholder="{{ trans('general.form.enter', ['field' => trans('general.procedures')]) }}"/>

                                <x-form.inputs.text type="text" id="equipment"
                                                    label="{{ trans('general.equipment') }}"
                                                    class="col-6 required"
                                                    value="{{ old('equipment') }}"
                                                    placeholder="{{ trans('general.form.enter', ['field' => trans('general.equipment')]) }}"/>

                                <x-form.inputs.text type="text" id="supplies"
                                                    label="{{ trans('general.supplies') }}"
                                                    class="col-6 required"
                                                    value="{{ old('supplies') }}"
                                                    placeholder="{{ trans('general.form.enter', ['field' => trans('general.supplies')]) }}"/>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                                class="fas fa-times"></i> {{ trans('general.cancel') }}</button>
                    <button class="btn btn-primary">
                        <i class="fas fa-save pr-2"></i> {{ trans('general.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>