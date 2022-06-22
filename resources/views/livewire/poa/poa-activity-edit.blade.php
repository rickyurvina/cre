<div>
    <div
            x-cloak
            x-data="poa_activity_edit"
            x-init="
            $watch('show', value => {
                if (value) {
                    $('#poa-activity-edit').modal('show');
                } else {
                    $('#poa-activity-edit').modal('hide');
                }
            });

"
            x-on:keydown.escape.window="show = false"
            x-on:close.stop="show = false"
    >
        <div class="modal fade" id="poa-activity-edit"
             data-backdrop="static" data-keyboard="false"
             tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-right modal-lg">
                <div class="modal-content">
                    <div class="modal-header w-75 mx-auto">
                        @if($activity)
                            <span
                                    class="fs-sm text-muted text-truncate">{{ $activity->indicator->indicatorable->name }} / {{ $activity->indicator->name }}</span>
                        @endif
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" x-on:click="show = false">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="w-75 mx-auto">
                            <div class="d-flex align-items-center justify-content-between">
                                @if($activity)
                                    <livewire:components.input-inline-edit :modelId="$activity->id"
                                                                           class="\App\Models\Poa\PoaActivity"
                                                                           field="name"
                                                                           defaultValue="{{$activity->name}}"
                                                                           :key="time().$activity->id"/>

                                    <button class="btn btn-xs btn-danger fs-xl shadow-0 w-25 ml-2"
                                            wire:click="$emit('triggerDelete', '{{ $activity->id }}')"
                                            x-on:click="show = false">
                                        <i class="fas fa-trash"></i> {{ __('general.delete') }}
                                    </button>
                                @endif
                            </div>
                            <div class="section-divider"></div>
                            <div class="row">
                                @if($activity)
                                    <div class="form-group col-12" wire:ignore>
                                        <label class="form-label" for="select234">
                                            {{ trans('general.poa_activity_beneficiaries') }}
                                        </label>
                                        <select class="form-control" multiple="multiple" id="select234">
                                            @foreach($beneficiaries as $item)
                                                <option value="{{ $item->id }}" {{ in_array($item->id, $this->auxBeneficiaries) ? 'selected':'' }}>{{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12" wire:ignore>
                                    <label class="form-label" for="select3">
                                        {{ trans('general.poa_activity_location') }}
                                    </label>
                                    <select class="form-control @error($poaActivityLocationId) is-invalid @enderror" id="select3">
                                    </select>
                                    @error($poaActivityLocationId)
                                    <div
                                            class="invalid-feedback">{{ $errors->first($poaActivityLocationId) }}</div> @enderror
                                </div>
                            </div>
                            <div class="section-divider"></div>
                            <div class="d-flex align-items-center">
                                <span class="fs-2x w-40px"><i class="fal fa-paperclip"></i></span>
                                <span class="fs-2x fw-700">Archivos adjuntos</span>
                            </div>

                            <div class="card-body pt-0">
                                <div class="w-100 mx-auto">
                                    <div class="row">
                                        <div class="form-group col-12 pl-6 pt-4">
                                            <x-fileupload
                                                    wire:model.defer="fileEdit"
                                                    allowRevert
                                                    allowRemove
                                                    allowFileSizeValidation
                                                    maxFileSize="4mb"></x-fileupload>
                                            @error('fileEdit')
                                            <div class="alert alert-danger fade show" role="alert">
                                                {{__('general.file_required')}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-5">
                                            <label class="form-label"
                                                   for="filecoments">{{ trans('general.observations') }}</label>
                                            <textarea wire:model.defer="observationEdit" rows="1" id="filecoments"
                                                      class="form-control bg-transparent @error($observationEdit) is-invalid @enderror"></textarea>
                                            <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('observationEdit',':message') }} </div>
                                        </div>
                                        <div class="form-group col-4">
                                            <label class="form-label" for="start_date">{{ trans('general.date') }}</label>
                                            <div class="input-group bg-white shadow-inset-2">
                                                <input class="form-control @error($date) is-invalid @enderror" id="start_date" type="month" name="start_date"
                                                       wire:model.defer="date">
                                            </div>
                                            <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('date',':message') }} </div>
                                        </div>

                                        <div class="form-group col-3 pt-4">
                                            <button class="btn btn-primary " wire:click="addEditFile">
                                                <i class="fas fa-plus pr-2"></i> {{ trans('general.add') }}
                                            </button>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel-content">
                                    <div class="accordion accordion-clean" id="js_demo_accordion-1">
                                        @foreach($files as $index => $file_s)
                                            @if(count($file_s)>0)
                                                <div class="card">
                                                    <div class="card-header">
                                                        <a href="javascript:void(0);" class="card-title" data-toggle="collapse" data-target="#js_demo_accordion-1{{$index}}"
                                                           aria-expanded="false">
                                                            <span class="mr-2">
                                                                <span class="collapsed-reveal">
                                                                    <i class="fal fa-minus fs-xl"></i>
                                                                </span>
                                                                <span class="collapsed-hidden">
                                                                    <i class="fal fa-plus fs-xl"></i>
                                                                </span>
                                                            </span>
                                                            {{$months_list[$index]}}
                                                        </a>
                                                    </div>
                                                    <div id="js_demo_accordion-1{{$index}}" class="collapse" data-parent="#js_demo_accordion-1">
                                                        <div class="card-body">
                                                            <table class="table m-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>{{ __('general.file_name') }}</th>
                                                                    <th>{{ __('general.observations') }}</th>
                                                                    <th>{{trans('general.user')}}</th>
                                                                    <th>{{ __('general.date') }}</th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                @foreach($file_s as $index => $file)
                                                                    <tr>
                                                                        <td>
                                                                            <a href="#" wire:click="download({{ $file['id'] }})">{{ $file['name'] }}
                                                                            </a>
                                                                        </td>
                                                                        <td>{{ $file['observation'] }}</td>
                                                                        <td>{{ \App\Models\Auth\User::find( $file['user_id'] )->name }}</td>
                                                                        <td>{{ $file['date'] }}</td>
                                                                        <td><span wire:click="removeEditFile('{{ $file['name'] }}')" class="cursor-pointer trash"><i class="fas fa-trash
                                                                                                                    text-danger"></i></span></td>
                                                                    </tr>
                                                                @endforeach

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="section-divider"></div>

                            <div class="d-flex align-items-center">
                                <span class="fs-2x w-40px"><i class="fal fa-comment-dots"></i></span>
                                <span class="fs-2x fw-700">Comentarios</span>
                            </div>

                            @if($activity)
                                <livewire:components.comments :modelId="$activity->id" class="\App\Models\Poa\PoaActivity"
                                                              :key="time().$activity->id"/>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('page_script')
    <script>

        $(document).ready(function () {


            // default list filter
            initApp.listFilter($('#js_default_list'), $('#js_default_list_filter'));
            // custom response message
            initApp.listFilter($('#js-list-msg'), $('#js-list-msg-filter'));
            //accordion filter
            initApp.listFilter($('#js_list_accordion'), $('#js_list_accordion_filter'));
            // nested list filter
            initApp.listFilter($('#js_nested_list'), $('#js_nested_list_filter'));
            //init navigation
            initApp.buildNavigation($('#js_nested_list'));


            $('#select3').select2({
                dropdownParent: $("#poa-activity-edit"),
                placeholder: "{{ trans('general.select').' '.trans('general.poa_activity_location') }}",
                ajax: {
                    url: '{{ route('catalog.geographic.search', ['type' => \App\Models\Common\CatalogGeographicClassifier::TYPE_PARISH]) }}',
                    dataType: 'json',
                    delay: 100,
                    cache: true,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: (data) => {
                        return {
                            results: data
                        };
                    }
                }
            }).on('change', function (e) {
            @this.set('poaActivityLocationId', $(this).val());
                Livewire.emit('locationUpdated');
            });
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('poa_activity_edit', () => ({
                show: @entangle('show').defer
            }));
        });

        document.addEventListener('DOMContentLoaded', function () {
        @this.on('triggerDelete', id => {
            Swal.fire({
                title: '{{ trans('messages.warning.sure') }}',
                text: '{{ trans('messages.warning.delete') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
            }).then((result) => {
                //if user clicks on delete
                if (result.value) {
                    // calling destroy method to delete
                @this.call('deleteActivity', id);
                }
            });
        });
        })

        window.addEventListener('loadLocation', event => {
            $('#select234').select2({
                dropdownParent: $("#poa-activity-edit"),
                placeholder: "{{ trans('general.select').' '.trans('general.poa_activity_beneficiaries') }}",
            }).on('change', function (e) {
            @this.set('beneficiariesSelected', $(this).val());
            });
            let data = {
                id: event.detail.id,
                text: event.detail.description
            };

            $("#select3").empty();
            let newOption = new Option(data.text, data.id, false, false);
            $('#select3').append(newOption);
        });
    </script>
@endpush
