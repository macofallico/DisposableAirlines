<div class="card mb-2">
  <div class="card-header p-1">
    <h5 class="m-1 p-0">
      @lang('DisposableAirlines::common.lreports'): {{ $aircraft->registration }}
      <i class="fas fa-upload float-right"></i>
    </h5>
  </div>
  <div class="card-body p-0">
    <table class="table table-sm table-borderless table-striped text-center mb-0">
      <tr>
        <th class="text-left">@lang('DisposableAirlines::common.flightid')</th>
        <th>@lang('DisposableAirlines::common.orig')</th>
        <th>@lang('DisposableAirlines::common.dest')</th>
        <th>@lang('DisposableAirlines::common.dist')</th>
        <th>@lang('DisposableAirlines::common.ftime')</th>
        <th>@lang('DisposableAirlines::common.fuelused')</th>
        <th>@lang('pireps.submitted')</th>
        <th>@lang('DisposableAirlines::common.pic')</th>
      </tr>
      @foreach($pireps as $pirep)
        <tr>
          <td class="text-left">
            @ability('admin', 'admin-access')<a href="{{ route('frontend.pireps.show', [$pirep->id]) }}"><i class="fas fa-info-circle" style="margin-right: 5px;"></i></a> @endability
            <b>{{ $pirep->airline->iata }} {{ $pirep->ident }}</b>
          </td>
          <td>
            <a href="{{ route('frontend.airports.show', [$pirep->dpt_airport_id]) }}" title="{{ $pirep->dpt_airport->name ?? '' }}">{{ $pirep->dpt_airport_id }}</a>
          </td>
          <td>
            <a href="{{ route('frontend.airports.show', [$pirep->arr_airport_id]) }}" title="{{ $pirep->arr_airport->name ?? '' }}">{{ $pirep->arr_airport_id }}</a>
          </td>
          <td>
            @if($pirep->distance)
              {{ Dispo_Distance($pirep->distance) }}
            @endif
          </td>
          <td>
            @if($pirep->flight_time)
              @minutestotime($pirep->flight_time)
            @endif
          </td>
          <td>
            @if($pirep->fuel_used)
              {{ Dispo_Fuel($pirep->fuel_used) }}
            @endif
          </td>
          <td>
            @if(filled($pirep->submitted_at))
              {{ $pirep->submitted_at->diffForHumans() }}
            @endif
          </td>
          <td>
            <a href="{{ route('frontend.users.show.public', [$pirep->user_id]) }}">{{ $pirep->user->name_private }}</a>
          </td>
        </tr>
      @endforeach
    </table>
  </div>
</div>
