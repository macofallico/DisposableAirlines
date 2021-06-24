<?php

namespace Modules\DisposableAirlines\Listeners;

use App\Events\PirepUpdated;
use App\Models\Aircraft;
use App\Models\Enums\AircraftState;
use App\Models\Enums\PirepStatus;
use Log;

class PirepUpdatedEventListener
{
  // Listen PirepFiled Event
  // To Change Aircraft State : IN_AIR or IN_USE
  // According to Pirep Status (Boarding, TakeOff, Landed)
  public function handle(PirepUpdated $event)
  {
    if(Dispo_Settings('dairlines.acstate_control') && $event->pirep->aircraft) {
      $pirep = $event->pirep;
      if($pirep->status === PirepStatus::BOARDING || $pirep->status === PirepStatus::TAKEOFF || $pirep->status === PirepStatus::LANDED) {
        $pirep_aircraft = $event->pirep->aircraft;
        $aircraft = Aircraft::where('id', $pirep_aircraft->id)->first();
        if($aircraft && $pirep->status === PirepStatus::BOARDING) {
          $aircraft->state = AircraftState::IN_USE;
          $aircraft->save();
          Log::info("'Disposable Airlines: Pirep ".$event->pirep->id." BOARDING started, Change STATE of ".$aircraft->registration." to IN USE'");
        }
        elseif($aircraft && $pirep->status === PirepStatus::TAKEOFF) {
          $aircraft->state = AircraftState::IN_AIR;
          $aircraft->save();
          Log::info("'Disposable Airlines: Pirep ".$event->pirep->id." TAKE OFF reported, Change STATE of ".$aircraft->registration." to IN AIR'");
        }
        elseif($aircraft && $pirep->status === PirepStatus::LANDED) {
          $aircraft->state = AircraftState::IN_USE;
          $aircraft->save();
          Log::info("'Disposable Airlines: Pirep ".$event->pirep->id." LANDING reported, Change STATE of ".$aircraft->registration." to IN USE'");
        }
      }
    }
  }
}
