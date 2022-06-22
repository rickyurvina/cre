@extends('layouts.admin')

@section('title', trans('poa.card_reports'))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-table text-primary"></i> {{ trans_choice('general.poa_reports', 2) }}
    </h1>
@endsection

@section('content')
    <div class="row">
        @foreach($cardReports as $cardReport)
            <div class="col-sm-3 mb-2">
                <div class="card">
                    <a href="{{ route($cardReport['ruta']) }}">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold">{{ $cardReport['titulo'] }}</h5>
                            <p class="card-text">{{$cardReport['descripcion']}}</p>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('page_script')
    <script>

    </script>
@endpush