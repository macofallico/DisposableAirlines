<div class="card">
  <div class="card-header p-1">
    <h5 class="m-1 p-0">
      {{ $airline->name }} @lang('DisposableAirlines::common.fleet')
      <i class="fas fa-plane float-right"></i>
    </h5>
  </div>
  <div class="card-body p-1">
    @if($airline->subfleets->count())
      @foreach($airline->subfleets->sortBy('name') as $subfleet)
        @if(!$loop->first)<hr class="m-0 p-0">@endif
        <table class="table table-sm table-borderless text-left mb-0">
          <tr>
            <td>
              <i class="fas fa-angle-double-down ml-1 mr-2" type="button" data-toggle="collapse" data-target="#list{{ $subfleet->id }}" aria-expanded="false" aria-controls="list{{ $subfleet->id }}" 
                title="@lang('DisposableAirlines::common.showhide')"></i>
              <a href="{{ route('DisposableAirlines.dsubfleet', [$subfleet->type]) }}">{{ $subfleet->name }}</a>
            </td>
            <td class="text-right">
              @if($subfleet->aircraft->count())
                {{ $subfleet->aircraft->count() }}
              @endif
            </td>
          </tr>
        </table>
        <div id="list{{ $subfleet->id }}" class="collapse">
          @include('DisposableAirlines::airline_fleet_table')
        </div>
      @endforeach
    @endif
  </div>
</div>
