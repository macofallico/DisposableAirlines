<?php

namespace Modules\DisposableAirlines\Listeners;

use App\Events\PirepFiled;
use App\Models\Aircraft;
use App\Models\Enums\AircraftState;
use Log;

class PirepFiledEventListener
{
  // Listen PirepFiled Event
  // To Change Aircraft State : PARKED
  public function handle(PirepFiled $event)
  {
    if($event->pirep->aircraft) {
      $pirep_aircraft = $event->pirep->aircraft;
      $aircraft = Aircraft::where('id', $pirep_aircraft->id)->first();
      if($aircraft) {
        $aircraft->state = AircraftState::PARKED;
        $aircraft->save();
        Log::info("'Disposable Airlines: Pirep ".$event->pirep->id." FILED, Change STATE of ".$aircraft->registration." to ON GROUND'");
      }
    }
  }
}
