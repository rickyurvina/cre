<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header btn-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ trans('general.update') }} {{ trans_choice('project.line_action_service_activities', 1) }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="code" class="form-label required">{{ __('general.code') }}</label>
                        <input type="text"
                               class="form-control @error('code') is-invalid @enderror"
                               id="code"
                               wire:model.defer="code" />
                        @error('code') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label required">{{ __('general.name') }}</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               wire:model.defer="name" />
                        @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="description">{{ __('general.description') }}</label>
                        <input type="text"
                               class="form-control @error('description') is-invalid @enderror"
                               id="name"
                               wire:model.defer="description" />
                        @error('description') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="project_line_action_id" class="form-label required">
                            {{ trans_choice('project.line_action_services',1) }}
                        </label>
                        <select id="service_id"
                                name="service_id"
                                class="form-control @error('project_line_action_id') is-invalid @enderror"
                                wire:model.defer="service_id">
                            <option>--</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}"
                                        {{ $service_id == $service->id ? 'selected="selected"' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_line_action_id') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">
                    {{__('general.cancel')}}
                </button>
                <button type="button" wire:click.prevent="update()" id='update' class="btn btn-primary">
                    {{__('general.update')}}
                </button>
            </div>
        </div>
    </div>
</div>

@push('page_script')
<script>
    $('#updateModal').on('hide.bs.modal', function (e) {
        Livewire.emit('cancel');
    });
    window.livewire.on('projectLineActionServiceActivityStore', () => {
        $('#updateModal').modal('hide');
    })
</script>
@endpush


