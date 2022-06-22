<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header btn-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ trans('general.update') }} {{ trans('project.risks_classification') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="name" class="form-label required">{{ __('general.name') }}</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               wire:model.defer="name" />
                        @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
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
    window.livewire.on('projectLineActionStore', () => {
        $('#updateModal').modal('hide');
    })
</script>
@endpush


