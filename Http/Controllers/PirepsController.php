<?php

namespace Modules\DisposableAirlines\Http\Controllers;

use App\Contracts\Controller;
use App\Models\Pirep;
use App\Models\Enums\PirepState;

class PirepsController extends Controller
{
  // All Pireps (except inProgress flights)
  public function allpireps()
  {
    $pireps = Pirep::where('state', '!=', PirepState::IN_PROGRESS)->orderby('submitted_at', 'desc')->paginate(50);

    return view('DisposableAirlines::pireps',[
      'pireps'    => $pireps,
    ]);
  }
}
