<?php

namespace Modules\DisposableAirlines\Http\Controllers;

use App\Contracts\Controller;
use App\Repositories\AircraftRepository;
use App\Repositories\PirepRepository;
use App\Repositories\SubfleetRepository;
use Nwidart\Modules\Facades\Module;

class FleetController extends Controller
{
  private $aircraftRepo;
  private $pirepRepo;
  private $subfleetRepo;

  public function __construct(
    AircraftRepository $aircraftRepo,
    PirepRepository $pirepRepo,
    SubfleetRepository $subfleetRepo
  ) {
    $this->aircraftRepo = $aircraftRepo;
    $this->pirepRepo = $pirepRepo;
    $this->subfleetRepo = $subfleetRepo;
  }
  // Full Fleet
  // Return collection
  public function fleet()
  {
    $fleet = $this->aircraftRepo->orderby('registration', 'asc')->paginate(50);

    $DisposableHubs = Module::find('DisposableHubs');
    if ($DisposableHubs) { $DisposableHubs = $DisposableHubs->isEnabled(); }

    return view('DisposableAirlines::fleet',[
      'disphubs' => $DisposableHubs,
      'fleet'    => $fleet,
    ]);
  }

  // Selected SubFleet
  // Return mixed
  public function subfleet($type)
  {
    $DisposableHubs = Module::find('DisposableHubs');
    if ($DisposableHubs) { $DisposableHubs = $DisposableHubs->isEnabled(); }

    $subfleet = $this->subfleetRepo->where('type', $type)->first();
    $fleet = $this->aircraftRepo->where('subfleet_id', $subfleet->id)->orderby('registration', 'asc')->paginate(50);

    if (!$subfleet) {
      flash()->error('Subfleet Not Found !');
      return redirect(route('DisposableAirlines.dfleet'));
    }

    return view('DisposableAirlines::fleet',[
      'disphubs' => $DisposableHubs,
      'fleet'    => $fleet,
      'subfleet' => $subfleet,
    ]);
  }

  // Selected Aircraft
  // Return mixed
  public function aircraft($reg)
  {
    // Check Modules
    $DisposableTools = Module::find('DisposableTools');
    if ($DisposableTools) { $DisposableTools = $DisposableTools->isEnabled(); }

    $DisposableHubs = Module::find('DisposableHubs');
    if ($DisposableHubs) { $DisposableHubs = $DisposableHubs->isEnabled(); }

    $DisposableTech = Module::find('DisposableTech');
    if ($DisposableTech) { $DisposableTech = $DisposableTech->isEnabled(); }

    $TurkSim = Module::find('TurkSim');
    if ($TurkSim) { $TurkSim = $TurkSim->isEnabled(); }

    $aircraft = $this->aircraftRepo->where('registration', $reg)->first();

    if (!$aircraft) {
      flash()->error('Aircraft Not Found !');
      return redirect(route('DisposableAirlines.dfleet'));
    }

    // Get Latest Pireps
    $pireps = $this->pirepRepo->where(
      [
        'aircraft_id' => $aircraft->id,
        'state'       => 2,
        'status'      => 'ONB',
      ])->orderby('submitted_at', 'desc')->take(5)->get();

    // Get Aircraft or Subfleet Image
    $acimage = strtolower('image/aircraft/'.$aircraft->registration.'.jpg');
    $sfimage = strtolower('image/subfleet/'.$aircraft->subfleet->type.'.jpg');

    if (is_file($acimage)) { $showimage = $acimage; }
    elseif (is_file($sfimage)) { $showimage = $sfimage; }

    // Get Maintenance Records
    if ($TurkSim) {
      $Maintenance = app(\Modules\TurkSim\Models\TurkSim_Maintenance::class);
      $maint = $Maintenance::where('aircraft_id', $aircraft->id)->first();
    }

    // Get Aircraft Specs
    if ($DisposableTech) {
      $specs = Dispo_GetAcSpecs($aircraft);
    }

    // Get Passenger Weight
    $paxwgt = setting('simbrief.noncharter_pax_weight');
    if (setting('units.weight') === 'kg') { $paxwgt = round($paxwgt / 2.20462262185, 2) ;}

    return view('DisposableAirlines::aircraft',[
      'aircraft'  => $aircraft,
      'disphubs'  => $DisposableHubs,
      'disptech'  => $DisposableTech,
      'disptools' => $DisposableTools,
      'maint'     => isset($maint) ? $maint : null,
      'paxwgt'    => $paxwgt,
      'pireps'    => $pireps,
      'showimage' => isset($showimage) ? $showimage : null,
      'specs'     => isset($specs) ? $specs : null,
      'turksim'   => $TurkSim,
    ]);
  }
}
