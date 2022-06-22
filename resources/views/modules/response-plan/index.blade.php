@extends('layouts.admin')

@section('title', trans('general.response-plan'))

@section('subheader')
  <h1 class="subheader-title">
    <i class="fal fa-list text-primary"></i> <span class="fw-300">{{ trans('general.response-plan') }}</span>
  </h1>
@endsection

@section('content')

{{--  @include('flash::message')--}}

  <div>
    @if (session()->has('message'))
      <div class="alert alert-success">
        {{ session('message') }}
      </div>
    @endif
  </div>

  <!--Componentes-->
  <livewire:response-plan.add-response-plan :id="1"/>

@endsection

@push('page_script')
  <script>
      $('#edit-response-plan-modal').on('show.bs.modal', function (e) {
          //get level ID & plan registered template detail ID
          let responsePlanId = $(e.relatedTarget).data('response-plan-id');
          //Livewire event trigger
          Livewire.emit('loadUpdateFormResponsePlan', responsePlanId);
      });

      $('#create-action-modal').on('show.bs.modal', function (e) {
          //get level ID & plan registered template detail ID
          let responsePlanId = $(e.relatedTarget).data('response-plan-id');
          //Livewire event trigger
          Livewire.emit('loadCreateFormAction', responsePlanId);
      });

      $('#create-task-modal').on('show.bs.modal', function (e) {
          //get level ID & plan registered template detail ID
          let actionId = $(e.relatedTarget).data('action-id');
          //Livewire event trigger
          Livewire.emit('loadCreateFormTask', actionId);
      });

      $('#list-action-modal').on('show.bs.modal', function (e) {
          //get level ID & plan registered template detail ID
          let responsePlanId = $(e.relatedTarget).data('response-plan-id');
          //Livewire event trigger
          Livewire.emit('loadShowFormAction', responsePlanId);
      });

  </script>
@endpush