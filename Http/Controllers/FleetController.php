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
    $DisposableHubs = Module::has('DisposableHubs');

    return view('DisposableAirlines::fleet',[
      'fleet'    => $fleet,
      'disphubs' => $DisposableHubs,
    ]);
  }

  // Selected SubFleet
  // Return mixed
  public function subfleet($type)
  {
    $DisposableHubs = Module::has('DisposableHubs');
    $subfleet = $this->subfleetRepo->where('type', $type)->first();
    $fleet = $this->aircraftRepo->where('subfleet_id', $subfleet->id)->orderby('registration', 'asc')->paginate(50);

    if(!$subfleet) {
      flash()->error('Subfleet Not Found !');
      return redirect(route('DisposableAirlines.dfleet'));
    }

    return view('DisposableAirlines::fleet',[
      'disphubs' => $DisposableHubs,
      'subfleet' => $subfleet,
      'fleet'    => $fleet,
    ]);
  }

  // Selected Aircraft
  // Return mixed
  public function aircraft($reg)
  {
    $DisposableTools = Module::has('DisposableTools');
    $DisposableHubs = Module::has('DisposableHubs');
    $aircraft = $this->aircraftRepo->where('registration', $reg)->first();
    
    if(!$aircraft) {
      flash()->error('Aircraft Not Found !');
      return redirect(route('DisposableAirlines.dfleet'));
    }

    $pireps = $this->pirepRepo->where('aircraft_id', $aircraft->id)->take(5)->get();
    $showimage = null;
    $acimage = strtolower('image/aircraft/'.$aircraft->registration.'.jpg');
    $sfimage = strtolower('image/subfleet/'.$aircraft->subfleet->type.'.jpg');
    if(is_file($acimage)) { $showimage = $acimage; } 
    elseif(is_file($sfimage)) { $showimage = $sfimage; }

    return view('DisposableAirlines::aircraft',[
      'disptools' => $DisposableTools,
      'disphubs'  => $DisposableHubs,
      'aircraft'  => $aircraft,
      'pireps'    => $pireps,
      'showimage' => $showimage,
    ]);
  }
}
