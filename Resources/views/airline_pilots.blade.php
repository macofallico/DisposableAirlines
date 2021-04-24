<div class="card">
  <div class="card-header p-1">
    <h5 class="m-1 p-0">
      {{ $airline->name }} @lang('DisposableAirlines::common.pilots')
      <i class="fas fa-users float-right"></i>
    </h5>
  </div>
  <div class="card-body p-0 overflow-auto" style="max-height: 600px;">
    {{--
      You Can Use either your main roster design or a specific desing to display Airline Pilots
      Examples for both are below (active is module roster)
      Module Table : @include('DisposableAirlines::airline_pilots_table')
      Theme Table  : @include('users.table')
    --}}
    @include('DisposableAirlines::airline_pilots_table')
  </div>
  <div class="card-footer p-1 text-right">
    @lang('DisposableAirlines::common.totpilots'): {{ $users->count() }}
  </div>
</div>
