<?php

namespace Modules\DisposableAirlines\Http\Controllers;

use App\Contracts\Controller;
use App\Events\PirepCancelled;
use App\Models\Aircraft;
use App\Models\Pirep;
use App\Models\Enums\AircraftState;
use App\Models\Enums\PirepState;
use App\Models\Enums\PirepStatus;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Log;

class AdminController extends Controller
{
  // Fix Aircraft State Manually
  public function FixAircraftState($reg) {
    $result = 0;
    $aircraft = Aircraft::where('registration', $reg)->where('state', '!=', AircraftState::PARKED)->first();
    if($aircraft) {
      $pirep = Pirep::where('aircraft_id', $aircraft->id)->where('state', PirepState::IN_PROGRESS)->orderby('updated_at', 'desc')->first();
      if($pirep) {
        $pirep->state = PirepState::CANCELLED;
        $pirep->status = PirepStatus::CANCELLED;
        $pirep->notes = 'Cancelled By Admin';
        $pirep->save();
        $result = 1;
        event(new PirepCancelled($pirep));
        Log::info("Disposable Airlines Module: Pirep id=".$pirep->id." cancelled by Admin to fix aircraft state. Pirep State: CANCELLED");
      }
      $aircraft->state = AircraftState::PARKED;
      $aircraft->save();
      $result = $result + 1;
      Log::info("Disposable Airlines Module: Aircraft reg=".$aircraft->registration." was grounded by Admin. AC State: PARKED");
    }
    if($result === 0) { Flash::error('Nothing Done... Aircraft Not Found or was already PARKED'); }
    elseif($result === 1) { Flash::success('Aircraft State Changed Back to PARKED'); }
    elseif($result === 2) { Flash::success('Aircraft State Changed Back to PARKED and Pirep CANCELLED'); }
  }

  // Admin Page
  public function admin(Request $request)
  {
    $acreg = $request->input('parkac');
    if($acreg) {
      $this->FixAircraftState($acreg);
    }
    return view('DisposableAirlines::admin');
  }
}
