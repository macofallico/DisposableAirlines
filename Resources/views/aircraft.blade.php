@extends('app')
@section('title', $aircraft->registration)

@section('content')
  <div class="row">
    <div class="col-6">
      @include('DisposableAirlines::aircraft_details')

      @if(count($aircraft->files) > 0 && Auth::check())
        <div class="card mb-2">
          <div class="card-header p-1">
            <h5 class="m-1 p-0">
              {{ trans_choice('common.download',2) }}
              <i class="fas fa-download float-right"></i>
            </h5>
          </div>
          <div class="card-body p-0">
            @include('downloads.table', ['files' => $aircraft->files])
          </div>
        </div>
      @endif
    </div>

    {{-- Show ac or subfleet image if there is one --}}
    <div class="col-6">
      @if($showimage)
        <div class="card mb-2">
          <div class="card-header p-1">
            <h5 class="m-1 p-0">
              {{ $aircraft->subfleet->airline->name }} {{ $aircraft->icao }}
              <i class="fas fa-camera-retro float-right"></i>
            </h5>
          </div>
          <div class="card-body p-0">
            <img src="{{ public_asset($showimage) }}"  width='100%'/>
          </div>
        </div>
      @endif
    </div>
  </div>

  {{-- Second Row For Aircraft Pireps and Stats --}}
  <div class="row">
    <div class="col-8">
      @include('DisposableAirlines::aircraft_pireps')
    </div>
    <div class="col-4">
      @if($disptools)
        @widget('Modules\DisposableTools\Widgets\AircraftStats', ['id' => $aircraft->id])
      @endif
    </div>
  </div>
@endsection
