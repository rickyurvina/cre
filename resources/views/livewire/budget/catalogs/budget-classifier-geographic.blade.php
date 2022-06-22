<div>
    <div class="card">
        <x-search route="{{ route('budget_catalogs.index') }}"/>
        <div class="table-responsive">
            <table class="table m-0">
                <thead class="bg-primary-50">
                <tr>
                    <th>@sortablelink('description', trans('general.name'))</th>
                    <th>@sortablelink('code', trans('general.code'))</th>
                    <th>@sortablelink('full_code', trans('general.full_code'))</th>
                    <th>@sortablelink('level', trans('general.type'))</th>
                    @can('budget-crud-budget')
                    <th class="text-center color-primary-500">{{ trans('general.actions') }}</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($geographicClassifier as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->full_code }}</td>
                        <td>{{ $item->type }}</td>
                        @can('budget-crud-budget')
                        <td class="text-center">

                        </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pagination :items="$geographicClassifier"/>
    </div>

</div>
