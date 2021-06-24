<?php

namespace Modules\DisposableAirlines\Listeners;

use App\Events\PirepPrefiled;
use App\Models\Aircraft;
use App\Models\Enums\AircraftState;
use Log;

class PirepPrefiledEventListener
{
  // Listen PirepPrefiled Event
  // To Change Aircraft State : IN_USE
  // Do this only for MANUAL Pireps ! ACARS Pireps will use Pirep Updated Event
  public function handle(PirepPrefiled $event)
  {
    if(Dispo_Settings('dairlines.acstate_control') && $event->pirep->aircraft && $event->pirep->source === 0) {
      $pirep_aircraft = $event->pirep->aircraft;
      $aircraft = Aircraft::where('id', $pirep_aircraft->id)->first();
      if($aircraft) {
        $aircraft->state = AircraftState::IN_USE;
        $aircraft->save();
        Log::info("'Disposable Airlines: Pirep ".$event->pirep->id." PRE-FILED, Change STATE of ".$aircraft->registration." to IN USE'");
      }
    }
  }
}
