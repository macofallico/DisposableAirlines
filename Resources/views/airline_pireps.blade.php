<div class="card">
  <div class="card-header p-1">
    <h5 class="m-1 p-0">
      {{ $airline->name }} @lang('DisposableAirlines::common.pireps')
      <i class="fas fa-upload float-right"></i>
    </h5>
  </div>
  <div class="card-body p-0 overflow-auto" style="max-height: 600px;">
    <table class="table table-sm table-borderless table-striped text-left mb-0">
      <tr>
          <th>@lang('DisposableAirlines::common.flightid')</th>
          <th>@lang('DisposableAirlines::common.orig')</th>
          <th>@lang('DisposableAirlines::common.dest')</th>
          <th>@lang('common.aircraft')</th>
          <th class="text-center">@lang('DisposableAirlines::common.btime')</th>
          {{-- <th class="text-center">@lang('DisposableAirlines::common.score')</th> --}}
          {{-- <th class="text-center">@lang('DisposableAirlines::common.lndrate')</th> --}}
          <th class="text-center">@lang('DisposableAirlines::common.psubmit')</th>
          <th>@lang('DisposableAirlines::common.pic')</th>
          <th class="text-center">@lang('common.status')</th>
      </tr>
      @foreach($pireps as $pirep)
        <tr>
          <td>
              @ability('admin', 'admin-access')<a href="{{ route('frontend.pireps.show', [$pirep->id]) }}"><i class="fas fa-info-circle" style="margin-right: 5px;"></i></a> @endability
              <b>{{ $pirep->airline->code }} {{ $pirep->ident }}</b>
          </td>
          <td>
            <a href="{{ route('frontend.airports.show', [$pirep->dpt_airport_id]) }}" title="{{ $pirep->dpt_airport->iata ?? '' }} {{ $pirep->dpt_airport->name ?? '' }}">{{ $pirep->dpt_airport_id }}</a>
          </td>
          <td>
            <a href="{{ route('frontend.airports.show', [$pirep->arr_airport_id]) }}" title="{{ $pirep->arr_airport->iata ?? '' }} {{ $pirep->arr_airport->name ?? '' }}">{{ $pirep->arr_airport_id }}</a>
          </td>
          <td>
            @if($pirep->aircraft)
              <a href="{{ route('DisposableAirlines.daircraft', [$pirep->aircraft->registration]) }}">{{ $pirep->aircraft->registration }} ({{ $pirep->aircraft->icao }})</a>
            @endif
          </td>
          <td class="text-center">@minutestotime($pirep->flight_time)</td>
          {{-- <td class="text-center">{{ $pirep->score }}</td> --}}
          {{-- <td class="text-center">@if($pirep->landing_rate) {{ $pirep->landing_rate }} ft/min @endif</td> --}}
          <td class="text-center">
            @if(filled($pirep->submitted_at))
              {{ $pirep->submitted_at->diffForHumans() }}
            @endif
          </td>
          <td>
            <a href="{{ route('frontend.users.show.public', [$pirep->user->id]) }}">{{ $pirep->user->name_private }}</a>
          </td>
          <td class="text-center">{!! Dispo_PirepBadge($pirep->state) !!}</td>
        </tr>
      @endforeach
    </table>
  </div>
  <div class="card-footer p-1 text-right">
    @lang('DisposableAirlines::common.totpireps'): {{ $pireps->total() }}
  </div>
</div>

<div class="row mt-2">
  <div class="col text-center">
    {{ $pireps->links('pagination.default') }}
  </div>
</div>

{{-- Script For Pagination --}}
@if(request()->has('page'))
  @section('scripts')
    @parent
    <script>
      function ActivateTab(selection) {
        $('.nav-pills a[href="#pills-' + selection + '"]').tab('show');
      };
      ActivateTab('pireps');
    </script>
  @endsection
@endif
