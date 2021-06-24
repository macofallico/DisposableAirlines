<?php

namespace Modules\DisposableAirlines\Listeners;

use App\Events\PirepCancelled;
use App\Models\Aircraft;
use App\Models\Enums\AircraftState;
use Log;

class PirepCancelledEventListener
{
  // Listen PirepCancelled Event
  // To Change Aircraft State : PARKED (On Ground)
  public function handle(PirepCancelled $event)
  {
    if(Dispo_Settings('dairlines.acstate_control') && $event->pirep->aircraft) {
      $pirep = $event->pirep;
      if($pirep) {
        $pirep_aircraft = $pirep->aircraft;
        $aircraft = Aircraft::where('id', $pirep_aircraft->id)->first();
        if($aircraft) {
          $aircraft->state = AircraftState::PARKED;
          $aircraft->save();
          Log::info("'Disposable Airlines: Pirep ".$event->pirep->id." CANCELLED, Change STATE of ".$aircraft->registration." to ON GROUND'");
        }
      }
    }
  }
}
