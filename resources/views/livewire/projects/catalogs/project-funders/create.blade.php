<!-- Modal -->
<div wire:ignore.self  class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header btn-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ trans('general.add') }} {{ trans_choice('project.funders', 1) }}
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
                        <label for="type" class="form-label">{{ __('general.type') }}</label>
                        <input type="text"
                               class="form-control @error('type') is-invalid @enderror"
                               id="type"
                               wire:model.defer="type" />
                        @error('type') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">
                    {{__('general.cancel')}}
                </button>
                <button type="button" wire:click="store" class="btn btn-primary close-modal">
                    {{__('general.add')}}
                </button>
            </div>
        </div>
    </div>
</div>


@push('page_script')
    <script>
        $('#createModal').on('hide.bs.modal', function (e) {
            Livewire.emit('cancel');
        });
        window.livewire.on('projectFunderStore', () => {
            $('#createModal').modal('hide');
        })
    </script>
@endpush