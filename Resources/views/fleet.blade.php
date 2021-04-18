@extends('app')
@section('title', __('DisposableAirlines::common.fleet'))

@section('content')
  <div class="row">
    <div class="col">
      <div class="card mb-2">
        <div class="card-header p-1">
          <h5 class="p-0 m-1">
            @isset($subfleet) {{ $subfleet->name }} @else {{ config('app.name') }} @endisset @lang('DisposableAirlines::common.fleet')
            <i class="fas fa-plane-departure float-right"></i>
          </h5>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm table-borderless table-striped text-center mb-0">
            <tr>
              <th class="text-left">@lang('DisposableAirlines::common.reg')</th>
              <th>ICAO @lang('DisposableAirlines::common.type')</th>
              <th>MTOW</th>
              <th>@lang('DisposableAirlines::common.airline')</th>
              <th>@lang('DisposableAirlines::common.subfleet')</th>
              <th>@lang('DisposableAirlines::common.base')</th>
              <th>@lang('DisposableAirlines::common.location')</th>
              <th>@lang('DisposableAirlines::common.ftime')</th>
              <th>@lang('DisposableAirlines::common.lastlnd')</th>
              <th>@lang('DisposableAirlines::common.fuelob')</th>
              <th>@lang('common.state')</th>
              <th>@lang('common.status')</th>
            </tr>
            @foreach($fleet as $aircraft)
              <tr>
                <td class="text-left">
                  <a href="{{ route('DisposableAirlines.daircraft', [$aircraft->registration]) }}">{{ $aircraft->registration }} @if($aircraft->registration != $aircraft->name) '{{ $aircraft->name }}' @endif</a>
                </td>
                <td>{{ $aircraft->icao }}</td>
                <td>
                  @if ($aircraft->mtow > 0)
                    {{ number_format($aircraft->mtow) }} {{ setting('units.weight') }}
                  @endif
                </td>
                <td>
                  <a href="{{ route('DisposableAirlines.ashow', [$aircraft->subfleet->airline->icao]) }}">{{ $aircraft->subfleet->airline->name }}</a>
                </td>
                <td>
                  <a href="{{ route('DisposableAirlines.dsubfleet', [$aircraft->subfleet->type]) }}">{{ $aircraft->subfleet->name }}</a>
                </td>
                <td>
                  @if($aircraft->subfleet->hub_id && $disphubs)
                    <a href="{{ route('DisposableHubs.hshow', [strtoupper($aircraft->subfleet->hub_id)]) }}">{{ $aircraft->subfleet->hub_id }}</a>
                  @elseif($aircraft->subfleet->hub_id)
                    <a href="{{ route('frontend.airports.show', [strtoupper($aircraft->subfleet->hub_id)]) }}">{{ $aircraft->subfleet->hub_id }}</a>
                  @endif
                </td>
                <td>
                  @if($aircraft->airport_id)
                    <a href="{{ route('frontend.airports.show', [$aircraft->airport_id]) }}">{{ $aircraft->airport_id }}</a>
                  @endif
                </td>
                <td>
                  @if($aircraft->flight_time > 0)
                    @minutestotime($aircraft->flight_time)
                  @endif
                </td>
                <td>
                  @if($aircraft->landing_time)
                    {{ Carbon::parse($aircraft->landing_time)->diffForHumans() }}
                  @endif
                </td>
                <td>
                  @if($aircraft->fuel_onboard > 0)
                    {{ Dsp_Fuel($aircraft->fuel_onboard) }}
                  @endif
                </td>
                <td>
                  <span class="badge {{ Dsp_AcStateBadge($aircraft->state) }}">{{ \App\Models\Enums\AircraftState::label($aircraft->state) }}</span>
                </td>
                <td>
                  <span class="badge {{ Dsp_AcStatusBadge($aircraft->status) }}">{{ \App\Models\Enums\AircraftStatus::label($aircraft->status) }}</span>
                </td>
              </tr>
            @endforeach
          </table>
        </div>
        <div class="card-footer p-1 text-right">
          <div class="row row-cols-2">
            <div class="col text-left">
              @isset($subfleet)
                @lang('DisposableAirlines::common.config') : 
                @foreach($subfleet->fares as $fare)
                  @if(!$loop->first) &bull; @endif
                  {{ $fare->name }}
                  {{ number_format($fare->pivot->capacity) }}
                  @if($fare->type === 1) {{ setting('units.weight') }} @else Pax @endif
                @endforeach
              @endisset
            </div>
            <div class="col text-right">
              @lang('DisposableAirlines::common.totfleet') : {{ $fleet->total() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col text-center">
      {{ $fleet->links('pagination.default') }}
    </div>
  </div>
@endsection
