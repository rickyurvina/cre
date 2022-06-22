
<div class="subheader-block d-lg-flex align-items-center">
    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#add_piat_modal">
        <i class="fas fa-plus mr-1"></i>
        {{ trans('general.create') }}
    </button>
</div>

<div class="w-100">
    <div class="table-responsive">
        <table class="table table-light table-hover">
            <thead>
            <tr>
                <th class="w-15">@sortablelink('matrix.name', trans_choice('poa.piat_matrix_create_placeholder_name', 1))</th>
                <th class="w-15">@sortablelink('matrix.date', trans_choice('poa.piat_matrix_create_placeholder_date', 1))</th>
                <th class="w-25">@sortablelink('matrix.status', __('poa.piat_matrix_create_placeholder_status'))</th>
                <th class="w-5"><a href="#">{{ trans('general.actions') }} </a></th>
            </tr>
            </thead>
            <tbody>
            @forelse($matrix as $item)
                <tr class="tr-hover" wire:loading.class.delay="opacity-50">
                    <td>
                        <div class="d-flex align-items-center">
                            <span>{{ $item->name }}</span>
                        </div>
                    </td>
                    <td>{{ $item->date }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <div class="d-flex flex-wrap">
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_piat_modal"
                                data-id="{{$item->id}}" class="">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#report_piat_modal"
                                data-id="{{$item->id}}" class="">
                                <i class="fas fa-book"></i>
                            </a>
                            <x-delete-link action="{{ route('poa.activity.delete_piat_matrix', $item) }}" id="{{ $item->id }}"/>
                        </div>
                    </td>
                </tr>
            
            @empty
                <tr>
                    <td colspan="7">
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="color-fusion-500 fs-3x py-3"><i class="fas fa-exclamation-triangle color-warning-900"></i> No se encontraron matrices</span>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<livewire:poa.piat.poa-create-piat-modal :activity="$activity"/>
<livewire:poa.piat.poa-edit-piat-modal :activity="$activity"/>
<livewire:poa.piat.poa-report-piat-modal :activity="$activity"/>

@push('page_script')
    <script>
        Livewire.on('togglePiatEditModal', () => $('#edit_piat_modal').modal('toggle'));
        Livewire.on('togglePiatAddModal', () => $('#add_piat_modal').modal('toggle'));
        Livewire.on('togglePiatReportModal', () => $('#report_piat_modal').modal('toggle'));
        $('#edit_piat_modal').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let id = $(e.relatedTarget).data('id');
            //Livewire event trigger
            Livewire.emit('loadEditForm', id);
        });

        $('#report_piat_modal').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let id = $(e.relatedTarget).data('id');
            //Livewire event trigger
            Livewire.emit('loadReportForm', id);
        });

        $('#add_piat_modal').on('show.bs.modal', function (e) {
            //Livewire event trigger
            Livewire.emit('loadForm');
        });

    </script>
@endpush
