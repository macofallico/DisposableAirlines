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
      @if($showimage || $maint || $disptech)
        <div class="card mb-2">
          <div class="card-header p-0">
            <nav>
              <h5 class="m-0 p-0">
                <i class="fas fa-cogs float-right mt-2 mr-1 p-1"></i>
                <div class="nav nav-tabs m-0 p-0 border-0" id="nav-tab" role="tablist">
                @if($showimage)
                  <a class="nav-link active m-1 p-1 border-0" id="nav-image-tab" data-toggle="tab" href="#nav-image" role="tab" aria-controls="nav-image" aria-selected="true">
                    {{ $aircraft->subfleet->airline->name }} {{ $aircraft->icao }}
                  </a>
                @endif
                @if($maint)
                  <a class="nav-link @if(!$showimage) active @endif m-1 p-1 border-0" id="nav-maint-tab" data-toggle="tab" href="#nav-maint" role="tab" aria-controls="nav-maint" aria-selected="false">
                    Maintenance
                  </a>
                @endif
                @if($disptech && $specs)
                  @if(count($specs) > 0 && count($specs) <= 2)
                    @foreach($specs as $sp)
                      <a class="nav-link @if($loop->first && !$showimage && !$maint) active @endif m-1 p-1 border-0" id="nav-specs{{ $sp->id }}-tab" data-toggle="tab"
                          href="#nav-specs{{ $sp->id }}" role="tab" aria-controls="nav-specs{{ $sp->id }}" aria-selected="false">{{ $sp->saircraft }}</a>
                    @endforeach
                  @elseif(count($specs) > 2)
                    <a class="nav-link dropdown-toggle m-1 p-1 border-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Supported Addons</a>
                    <div class="dropdown-menu card-header">
                      @foreach($specs as $sp)
                        <a class="nav-link @if($loop->first && !$showimage && !$maint) active @endif m-1 p-1 border-0" id="nav-specs{{ $sp->id }}-tab" data-toggle="tab"
                            href="#nav-specs{{ $sp->id }}" role="tab" aria-controls="nav-specs{{ $sp->id }}" aria-selected="false">
                            {{ $sp->saircraft }}
                        </a>
                      @endforeach
                    </div>
                  @endif
                @endif
                </div>
              </h5>
            </nav>
          </div>
          <div class="card-body p-0">
            <div class="tab-content" id="nav-tabContent">
              @if($showimage)
                <div class="tab-pane fade show active" id="nav-image" role="tabpanel" aria-labelledby="nav-image-tab">
                  <img src="{{ public_asset($showimage) }}"  width='100%'/>
                </div>
              @endif
              @if($maint)
                <div class="tab-pane fade @if(!$showimage) show active @endif" id="nav-maint" role="tabpanel" aria-labelledby="nav-maint-tab">
                  @include('TurkSim::maintenance_table')
                </div>
              @endif
              @if($disptech && $specs)
                @foreach($specs as $sp)
                  <div class="tab-pane fade @if($loop->first && !$showimage && !$maint) show active @endif" id="nav-specs{{ $sp->id }}" role="tabpanel" aria-labelledby="nav-specs{{ $sp->id }}-tab">
                    @include('DisposableTech::specs_table')
                  </div>
                @endforeach
              @endif
            </div>
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
