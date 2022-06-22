@extends('layouts.admin')

@section('title', trans_choice('process.process',0))

@push('css')
    <style>
        .page-content {
            padding: 0 4px;
        }

        .subheader {
            margin-bottom: 0 !important;
        }
    </style>
@endpush

@section('content')
    <livewire:process.process-navigation :process="$process" :page="$page" :subMenu="$subMenu"/>
    <div class="w-100">
        @yield('process-page')
    </div>

@endsection

