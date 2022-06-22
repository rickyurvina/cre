<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header btn-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ trans('general.update') }} {{ trans_choice('project.line_actions', 1) }}
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
                        <label for="plan_detail_id" class="form-label required">
                            {{ trans_choice('poa.program',1) }}
                        </label>
                        <select id="plan_detail_id"
                                name="plan_detail_id"
                                class="form-control @error('plan_detail_id') is-invalid @enderror"
                                wire:model.defer="plan_detail_id">
                            <option>--</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                        @error('plan_detail_id') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <livewire:projects.catalogs.list-project-line-action-services
                            :model="$project_line_action_id" />
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
    window.livewire.on('projectLineActionStore', () => {
        $('#updateModal').modal('hide');
    })
</script>
@endpush


