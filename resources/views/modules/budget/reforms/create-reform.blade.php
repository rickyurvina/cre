@extends('layouts.admin')

@section('title', trans_choice('budget.reform', 2))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fas fa-money-bill mr-2"></i>{{trans('general.create')}} {{ trans_choice('general.reforms', 2) }} {{$transaction->number}}
    </h1>

@endsection

@section('content')
    <div wire:ignore>
        <livewire:budget.reforms.create-reform :transaction="$transaction"/>
    </div>
@endsection
