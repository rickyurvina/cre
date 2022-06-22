<div wire:ignore.self class="tab-pane fade" id="contacts" role="tabpanel">
    <br>
    <div class="d-flex position-relative ml-auto w-100">
        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_contact_modal">
            <i class="fas fa-user-plus mr-1"></i>
            {{ trans('general.title.add', ['type' => trans_choice('general.contacts', 1)] ) }}
        </button>
    </div>
    <br>
    @if (count($contacts))
    {{-- <x-search route="{{ route('companies.edit',$company) }}"/> --}}
    <div class="card-header pr-2 d-flex align-items-center flex-wrap">
        <div class="d-flex position-relative ml-auto w-100">
            <i class="fal fa-search position-absolute pos-left fs-lg px-3 py-2 mt-1"></i>
            <input type="text" id="searchContact" name="search" value="{{ request()->get('search', '') }}" class="form-control bg-subtlelight pl-6"
                   placeholder="{{ trans('general.search_placeholder') }}">
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-hover m-0">
            <thead class="bg-primary-50">
            <tr>
                <th class="d-none">@sortablelink('id', trans('general.id'))</th>
                <th>@sortablelink('names', trans('general.names'))</th>
                <th>@sortablelink('surnames', trans('general.surnames'))</th>
                <th>@sortablelink('email', trans('general.email'))</th>
                <th>@sortablelink('personal_phone', trans('general.personal_phone'))</th>
                <th>@sortablelink('created_at', trans('general.created'))</th>
                <th>@sortablelink('enabled', trans('general.enabled'))</th>
                @can('admin-crud-admin')
                <th class="text-center color-primary-500">{{ trans('general.actions') }}</th>
                @endcan
            </tr>
            </thead>
            <tbody id="contactTable">
            @foreach($contacts as $item)
                <tr>
                    <th class="d-none">{{ $item->id }}</th>
                    <th>{{ $item->names }}</th>
                    <td>{{ $item->surnames }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->personal_phone }}</td>
                    <td>@date($item->created_at)</td>
                    <td>
                        @if ($item->enabled)
                            <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                        @else
                            <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
                        @endif
                    </td>
                    @can('admin-crud-admin')
                    <td class="text-center w-20">
                        <a class="mr-2" wire:click="delete({{ $item->id }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar">
                            <i class="fas fa-trash mr-1 text-danger"></i>
                        </a>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_contact_modal"
                            data-id="{{$item->id}}" class="">
                            <i class="fas fa-pencil mr-1 text-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar"></i>
                        </a>
                    </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
        <x-empty-content>
            <x-slot name="title">
                {{ trans('general.no_contacts_found') }}
            </x-slot>
        </x-empty-content>
    @endif
    <x-pagination :items="$contacts"/>

    <livewire:admin.contact-create-modal :idCompany="$idCompany"/>
    <livewire:admin.contact-edit-modal/>

</div>
@push('page_script')
    <script>
        Livewire.on('toggleContactEditModal', () => $('#edit_contact_modal').modal('toggle'));
        Livewire.on('toggleContactAddModal', () => $('#add_contact_modal').modal('toggle'));
        $('#edit_contact_modal').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let id = $(e.relatedTarget).data('id');
            //Livewire event trigger
            Livewire.emit('openContactEditModal', id);
        });

        $("#add_contact_modal").on('hidden.bs.modal', function(e) {
                livewire.emit('resetForm');
        });

        $("#edit_contact_modal").on('hidden.bs.modal', function(e) {
                livewire.emit('resetForm');
        });

        $(document).ready(function(){
            $("#searchContact").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#contactTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endpush