@extends('layouts.admin')

@section('title', trans('poa.reports'))

@push('css')
    <style>
        .subheader{
            margin-bottom: 8px!important;
        }
    </style>
@endpush

@section('subheader')
@endsection

@section('content')
    <livewire:poa.poa-reports/>
@endsection
