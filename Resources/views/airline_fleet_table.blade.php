<table class="table table-sm table-striped table-borderless text-center mb-0">
  <tr>
    <th class="text-left">@lang('DisposableAirlines::common.reg') / @lang('common.name')</th>
    <th>ICAO/IATA</th>
    <th>@lang('DisposableAirlines::common.base')</th>
    <th>@lang('DisposableAirlines::common.location')</th>
    <th>@lang('pireps.flighttime')</th>
    <th>@lang('DisposableAirlines::common.lastlnd')</th>
    <th>@lang('DisposableAirlines::common.fuelob')</th>
    <th>@lang('common.state')</th>
    <th>@lang('common.status')</th>
  </tr>
  @foreach($fleet->where('subfleet_id', $subfleet->id) as $aircraft)
    <tr>
      <td class="text-left">
        <a href="{{ route('DisposableAirlines.daircraft', [$aircraft->registration]) }}">{{ $aircraft->registration }} @if($aircraft->registration != $aircraft->name)'{{ $aircraft->name }}'@endif</a>
      </td>
      <td>{{ $aircraft->icao ?? ''}} / {{ $aircraft->iata ?? ''}}</td>
      <td>
        @if($subfleet->hub_id && $disphubs)
          <a href="{{ route('DisposableHubs.hshow', [$subfleet->hub_id]) }}">{{ $subfleet->hub_id }}</a> 
        @else
          {{ $subfleet->hub_id ?? ''}}
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
